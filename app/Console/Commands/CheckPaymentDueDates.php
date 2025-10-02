<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\NotificationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckPaymentDueDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-payment-due-dates 
                            {--days=* : Specific days to check (default: 7,3,1,0)}
                            {--dry-run : Run without sending notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for payment due dates and send notifications to clients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de vencimentos de pagamento...');
        
        $dryRun = $this->option('dry-run');
        $customDays = $this->option('days');
        
        // Dias padrão para verificação (7, 3, 1 dias antes e no dia do vencimento)
        $daysToCheck = $customDays ?: [7, 3, 1, 0];
        
        $totalNotifications = 0;
        $totalErrors = 0;
        
        foreach ($daysToCheck as $days) {
            $this->info("Verificando vencimentos em {$days} dia(s)...");
            
            $result = $this->checkDueDatesForDays((int)$days, $dryRun);
            $totalNotifications += $result['sent'];
            $totalErrors += $result['errors'];
            
            $this->line("  - {$result['sent']} notificações enviadas");
            if ($result['errors'] > 0) {
                $this->warn("  - {$result['errors']} erros encontrados");
            }
        }
        
        $this->info("Verificação concluída!");
        $this->info("Total de notificações enviadas: {$totalNotifications}");
        
        if ($totalErrors > 0) {
            $this->warn("Total de erros: {$totalErrors}");
            return 1;
        }
        
        return 0;
    }

    /**
     * Check due dates for specific number of days.
     */
    private function checkDueDatesForDays(int $days, bool $dryRun): array
    {
        $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');
        $sent = 0;
        $errors = 0;
        
        // Buscar orçamentos aprovados com vencimento na data alvo
        $orcamentos = Orcamento::where('status', Orcamento::STATUS_APROVADO)
            ->whereDate('data_vencimento', $targetDate)
            ->whereColumn('valor_pago', '<', 'valor_total') // Ainda tem saldo devedor
            ->with(['cliente.user', 'pagamentos'])
            ->get();
            
        $this->line("    Encontrados {$orcamentos->count()} orçamento(s) com vencimento em {$targetDate}");
        
        foreach ($orcamentos as $orcamento) {
            try {
                if ($this->shouldSendNotification($orcamento, $days)) {
                    if (!$dryRun) {
                        $this->sendPaymentDueNotification($orcamento, $days);
                    }
                    $sent++;
                    
                    $this->line("    ✓ Notificação enviada para orçamento #{$orcamento->id} - {$orcamento->cliente->nome}");
                } else {
                    $this->line("    - Notificação já enviada para orçamento #{$orcamento->id}");
                }
            } catch (\Exception $e) {
                $errors++;
                $this->error("    ✗ Erro ao processar orçamento #{$orcamento->id}: {$e->getMessage()}");
                
                Log::error('Erro ao enviar notificação de vencimento', [
                    'orcamento_id' => $orcamento->id,
                    'days' => $days,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return ['sent' => $sent, 'errors' => $errors];
    }

    /**
     * Check if notification should be sent.
     */
    private function shouldSendNotification(Orcamento $orcamento, int $days): bool
    {
        // Verificar se o cliente tem usuário associado
        if (!$orcamento->cliente || !$orcamento->cliente->user) {
            return false;
        }

        // Verificar preferências de notificação
        $preferences = NotificationPreference::getOrCreateForUser($orcamento->cliente->user->id);
        if (!$preferences->payment_notifications) {
            return false;
        }

        // Verificar se os dias de notificação estão configurados
        $notificationDays = $preferences->notification_days ?? [7, 3, 1, 0];
        if (!in_array($days, $notificationDays)) {
            return false;
        }

        // Verificar se já foi enviada notificação para este orçamento e número de dias
        $existingNotification = Notification::where('user_id', $orcamento->cliente->user->id)
            ->where('type', Notification::TYPE_PAYMENT_DUE)
            ->whereJsonContains('data->orcamento_id', $orcamento->id)
            ->whereJsonContains('data->days_until_due', $days)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        return !$existingNotification;
    }

    /**
     * Send payment due notification.
     */
    private function sendPaymentDueNotification(Orcamento $orcamento, int $days): void
    {
        $user = $orcamento->cliente->user;
        $saldoRestante = $orcamento->valor_total - $orcamento->valor_pago;
        
        // Criar notificação
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => Notification::TYPE_PAYMENT_DUE,
            'title' => $this->getNotificationTitle($days),
            'message' => $this->getNotificationMessage($orcamento, $days, $saldoRestante),
            'data' => [
                'orcamento_id' => $orcamento->id,
                'orcamento_numero' => $orcamento->numero ?? $orcamento->id,
                'days_until_due' => $days,
                'due_date' => $orcamento->data_vencimento->format('Y-m-d'),
                'valor_total' => $orcamento->valor_total,
                'valor_pago' => $orcamento->valor_pago,
                'saldo_restante' => $saldoRestante,
                'cliente_nome' => $orcamento->cliente->nome
            ]
        ]);

        // Verificar se deve enviar email
        $preferences = NotificationPreference::getOrCreateForUser($user->id);
        if ($preferences->email_enabled) {
            $this->sendPaymentDueEmail($orcamento, $notification, $days);
        }

        Log::info('Notificação de vencimento enviada', [
            'notification_id' => $notification->id,
            'orcamento_id' => $orcamento->id,
            'user_id' => $user->id,
            'days_until_due' => $days
        ]);
    }

    /**
     * Get notification title based on days.
     */
    private function getNotificationTitle(int $days): string
    {
        switch ($days) {
            case 0:
                return 'Pagamento Vence Hoje';
            case 1:
                return 'Pagamento Vence Amanhã';
            default:
                return "Pagamento Vence em {$days} Dias";
        }
    }

    /**
     * Get notification message.
     */
    private function getNotificationMessage(Orcamento $orcamento, int $days, float $saldoRestante): string
    {
        $numero = $orcamento->numero ?? "#{$orcamento->id}";
        $valor = 'R$ ' . number_format($saldoRestante, 2, ',', '.');
        $dataVencimento = $orcamento->data_vencimento->format('d/m/Y');
        
        switch ($days) {
            case 0:
                return "O pagamento do orçamento {$numero} vence hoje ({$dataVencimento}). Valor pendente: {$valor}. Efetue o pagamento para evitar atrasos.";
            case 1:
                return "O pagamento do orçamento {$numero} vence amanhã ({$dataVencimento}). Valor pendente: {$valor}. Não se esqueça de efetuar o pagamento.";
            default:
                return "O pagamento do orçamento {$numero} vence em {$days} dias ({$dataVencimento}). Valor pendente: {$valor}. Organize-se para efetuar o pagamento.";
        }
    }

    /**
     * Send payment due email notification
     */
    private function sendPaymentDueEmail(Orcamento $orcamento, Notification $notification, int $daysUntilDue): void
    {
        try {
            if ($orcamento->cliente && $orcamento->cliente->email) {
                Mail::to($orcamento->cliente->email)->send(
                    new \App\Mail\PaymentDueAlert($orcamento, $notification, $daysUntilDue)
                );
                
                $this->info("Payment due email sent to: " . $orcamento->cliente->email);
                
                Log::info('Payment due email sent successfully', [
                    'orcamento_id' => $orcamento->id,
                    'notification_id' => $notification->id,
                    'days_until_due' => $daysUntilDue,
                    'recipient' => $orcamento->cliente->email
                ]);
            } else {
                $this->warn("Cannot send payment due email for budget {$orcamento->id} - no client email");
                
                Log::warning('Cannot send payment due email - no client email', [
                    'orcamento_id' => $orcamento->id,
                    'notification_id' => $notification->id
                ]);
            }
        } catch (\Exception $e) {
            $this->error("Failed to send payment due email for budget {$orcamento->id}: " . $e->getMessage());
            
            Log::error('Failed to send payment due email', [
                'orcamento_id' => $orcamento->id,
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}