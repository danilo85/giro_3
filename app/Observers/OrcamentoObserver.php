<?php

namespace App\Observers;

use App\Models\Orcamento;
use App\Models\HistoricoEntry;
use App\Models\Notification;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrcamentoObserver
{
    /**
     * Handle the Orcamento "created" event.
     */
    public function created(Orcamento $orcamento): void
    {
        $this->createHistoryEntry($orcamento, 'system', 'Orçamento criado', [
            'valor_total' => $orcamento->valor_total,
            'status' => $orcamento->status,
            'cliente' => $orcamento->cliente->nome ?? 'N/A'
        ]);
    }

    /**
     * Handle the Orcamento "updated" event.
     */
    public function updated(Orcamento $orcamento): void
    {
        \Log::info('DEBUG: OrcamentoObserver::updated chamado', [
            'orcamento_id' => $orcamento->id,
            'user_id' => auth()->id()
        ]);
        
        $changes = $orcamento->getChanges();
        $original = $orcamento->getOriginal();
        
        \Log::info('DEBUG: Mudanças detectadas', [
            'orcamento_id' => $orcamento->id,
            'changes' => $changes,
            'original' => $original
        ]);
        
        if (empty($changes)) {
            \Log::info('DEBUG: Nenhuma mudança detectada no orçamento', [
                'orcamento_id' => $orcamento->id
            ]);
            return;
        }

        // Detectar mudanças específicas
        if (isset($changes['status'])) {
            \Log::info('DEBUG: Mudança de status detectada', [
                'orcamento_id' => $orcamento->id,
                'status_anterior' => $original['status'] ?? 'N/A',
                'status_novo' => $changes['status']
            ]);

            $this->createHistoryEntry($orcamento, 'status_change', 'Status alterado', [
                'status_anterior' => $original['status'] ?? 'N/A',
                'status_novo' => $changes['status'],
                'motivo' => 'Alteração automática do sistema'
            ]);

            // Enviar notificação para mudança de status
            \Log::info('DEBUG: Iniciando envio de notificação de mudança de status', [
                'orcamento_id' => $orcamento->id,
                'status_anterior' => $original['status'],
                'status_novo' => $changes['status']
            ]);
            
            $this->sendStatusChangeNotification($orcamento, $original['status'], $changes['status']);
        }

        if (isset($changes['valor_total'])) {
            $this->createHistoryEntry($orcamento, 'system', 'Valor total alterado', [
                'valor_anterior' => $original['valor_total'] ?? 0,
                'valor_novo' => $changes['valor_total'],
                'diferenca' => ($changes['valor_total'] - ($original['valor_total'] ?? 0))
            ]);
        }

        // Outras mudanças gerais
        $ignoredFields = ['updated_at', 'status', 'valor_total'];
        $relevantChanges = array_diff_key($changes, array_flip($ignoredFields));
        
        if (!empty($relevantChanges)) {
            $this->createHistoryEntry($orcamento, 'system', 'Orçamento editado', [
                'campos_alterados' => array_keys($relevantChanges),
                'total_alteracoes' => count($relevantChanges)
            ]);
        }
    }

    /**
     * Handle the Orcamento "deleted" event.
     */
    public function deleted(Orcamento $orcamento): void
    {
        $this->createHistoryEntry($orcamento, 'system', 'Orçamento excluído', [
            'valor_total' => $orcamento->valor_total,
            'status_final' => $orcamento->status,
            'motivo' => 'Exclusão pelo usuário'
        ]);
    }

    /**
     * Handle the Orcamento "restored" event.
     */
    public function restored(Orcamento $orcamento): void
    {
        $this->createHistoryEntry($orcamento, 'system', 'Orçamento restaurado', [
            'valor_total' => $orcamento->valor_total,
            'status' => $orcamento->status,
            'motivo' => 'Restauração pelo usuário'
        ]);
    }

    /**
     * Create a history entry for the orcamento.
     */
    private function createHistoryEntry(Orcamento $orcamento, string $tipo, string $titulo, array $detalhes = []): void
    {
        try {
            HistoricoEntry::create([
                'orcamento_id' => $orcamento->id,
                'user_id' => Auth::id() ?? 1, // Fallback para sistema
                'type' => $tipo,
                'title' => $titulo,
                'description' => $this->generateDescription($tipo, $titulo, $detalhes),
                'entry_date' => now(),
                'metadata' => array_merge($detalhes, [
                    'created_via' => 'system',
                    'automatic' => true
                ])
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the main operation
            \Log::error('Erro ao criar entrada no histórico: ' . $e->getMessage(), [
                'orcamento_id' => $orcamento->id,
                'type' => $tipo,
                'title' => $titulo
            ]);
        }
    }

    /**
     * Generate a description based on the event type and details.
     */
    private function generateDescription(string $tipo, string $titulo, array $detalhes): string
    {
        switch ($tipo) {
            case 'system':
                if (isset($detalhes['valor_anterior'])) {
                    return sprintf(
                        'Valor total alterado de R$ %s para R$ %s (diferença: R$ %s).',
                        number_format($detalhes['valor_anterior'] ?? 0, 2, ',', '.'),
                        number_format($detalhes['valor_novo'] ?? 0, 2, ',', '.'),
                        number_format($detalhes['diferenca'] ?? 0, 2, ',', '.')
                    );
                }
                if (isset($detalhes['valor_total'])) {
                    return sprintf(
                        'Orçamento criado automaticamente pelo sistema. Valor: R$ %s, Status: %s, Cliente: %s',
                        number_format($detalhes['valor_total'] ?? 0, 2, ',', '.'),
                        ucfirst($detalhes['status'] ?? 'N/A'),
                        $detalhes['cliente'] ?? 'N/A'
                    );
                }
                if (isset($detalhes['campos_alterados'])) {
                    return sprintf(
                        'Orçamento editado automaticamente. %d campo(s) alterado(s): %s',
                        $detalhes['total_alteracoes'] ?? 0,
                        implode(', ', $detalhes['campos_alterados'] ?? [])
                    );
                }
                return $titulo . ' - Evento registrado automaticamente pelo sistema.';

            case 'status_change':
                return sprintf(
                    'Status alterado de "%s" para "%s" automaticamente pelo sistema.',
                    ucfirst($detalhes['status_anterior'] ?? 'N/A'),
                    ucfirst($detalhes['status_novo'] ?? 'N/A')
                );

            default:
                return $titulo . ' - Evento registrado automaticamente pelo sistema.';
        }
    }

    /**
     * Send notification for status change.
     */
    private function sendStatusChangeNotification(Orcamento $orcamento, string $oldStatus, string $newStatus): void
    {
        \Log::info('DEBUG: sendStatusChangeNotification iniciado', [
            'orcamento_id' => $orcamento->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
        
        try {
            // Verificar se o cliente tem preferências de notificação ativas
            $cliente = $orcamento->cliente;
            
            \Log::info('DEBUG: Verificando cliente', [
                'orcamento_id' => $orcamento->id,
                'cliente_exists' => !is_null($cliente),
                'cliente_id' => $cliente ? $cliente->id : null
            ]);
            
            if (!$cliente || !$cliente->user) {
                \Log::warning('DEBUG: Cliente não encontrado ou sem usuário associado', [
                    'orcamento_id' => $orcamento->id,
                    'cliente_exists' => !is_null($cliente),
                    'user_exists' => $cliente ? !is_null($cliente->user) : false,
                    'user_id' => $cliente && $cliente->user ? $cliente->user->id : null
                ]);
                return;
            }

            \Log::info('DEBUG: Cliente e usuário encontrados', [
                'orcamento_id' => $orcamento->id,
                'cliente_id' => $cliente->id,
                'user_id' => $cliente->user->id,
                'user_name' => $cliente->user->name
            ]);

            $preferences = NotificationPreference::getOrCreateForUser($cliente->user->id);
            
            \Log::info('DEBUG: Preferências de notificação obtidas', [
                'user_id' => $cliente->user->id,
                'preferences_id' => $preferences->id,
                'budget_notifications' => $preferences->budget_notifications ?? 'N/A',
                'email_enabled' => $preferences->email_enabled ?? 'N/A',
                'budget_approved' => $preferences->budget_approved ?? 'N/A',
                'budget_rejected' => $preferences->budget_rejected ?? 'N/A'
            ]);
            
            // Verificar se as notificações de orçamento estão habilitadas
            if (!$preferences->budget_notifications) {
                \Log::warning('DEBUG: Notificações de orçamento desabilitadas para o usuário', [
                    'user_id' => $cliente->user->id,
                    'orcamento_id' => $orcamento->id,
                    'budget_notifications' => $preferences->budget_notifications
                ]);
                return;
            }

            // Determinar o tipo de notificação baseado no novo status
            $notificationType = $this->getNotificationTypeForStatus($newStatus);
            
            \Log::info('DEBUG: Tipo de notificação determinado', [
                'new_status' => $newStatus,
                'notification_type' => $notificationType,
                'orcamento_id' => $orcamento->id
            ]);
            
            if (!$notificationType) {
                \Log::warning('DEBUG: Tipo de notificação não encontrado para o status', [
                    'new_status' => $newStatus,
                    'orcamento_id' => $orcamento->id
                ]);
                return;
            }

            $title = $this->getNotificationTitle($newStatus);
            $message = $this->getNotificationMessage($orcamento, $oldStatus, $newStatus);
            
            \Log::info('DEBUG: Dados da notificação preparados', [
                'orcamento_id' => $orcamento->id,
                'user_id' => $cliente->user->id,
                'type' => $notificationType,
                'title' => $title,
                'message' => $message
            ]);

            // Criar notificação no banco
            \Log::info('DEBUG: Tentando criar notificação no banco de dados', [
                'orcamento_id' => $orcamento->id,
                'user_id' => $cliente->user->id
            ]);
            
            $notification = Notification::create([
                'user_id' => $cliente->user->id,
                'type' => $notificationType,
                'title' => $title,
                'message' => $message,
                'data' => [
                    'orcamento_id' => $orcamento->id,
                    'orcamento_numero' => $orcamento->numero ?? $orcamento->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'valor_total' => $orcamento->valor_total,
                    'cliente_nome' => $cliente->nome
                ]
            ]);
            
            \Log::info('DEBUG: Notificação criada com sucesso', [
                'notification_id' => $notification->id,
                'orcamento_id' => $orcamento->id,
                'user_id' => $cliente->user->id
            ]);



            // Enviar email se habilitado
            if ($preferences->email_enabled) {
                $this->sendStatusChangeEmail($orcamento, $notification, $oldStatus, $newStatus);
            }

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificação de mudança de status: ' . $e->getMessage(), [
                'orcamento_id' => $orcamento->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Get notification type based on status.
     */
    private function getNotificationTypeForStatus(string $status): ?string
    {
        switch ($status) {
            case Orcamento::STATUS_APROVADO:
                return Notification::TYPE_BUDGET_APPROVED;
            case Orcamento::STATUS_REJEITADO:
                return Notification::TYPE_BUDGET_REJECTED;
            case Orcamento::STATUS_QUITADO:
                return Notification::TYPE_BUDGET_PAID;
            default:
                return null;
        }
    }

    /**
     * Get notification title based on status.
     */
    private function getNotificationTitle(string $status): string
    {
        switch ($status) {
            case Orcamento::STATUS_APROVADO:
                return 'Orçamento Aprovado';
            case Orcamento::STATUS_REJEITADO:
                return 'Orçamento Rejeitado';
            case Orcamento::STATUS_QUITADO:
                return 'Orçamento Quitado';
            default:
                return 'Status do Orçamento Alterado';
        }
    }

    /**
     * Get notification message.
     */
    private function getNotificationMessage(Orcamento $orcamento, string $oldStatus, string $newStatus): string
    {
        $numero = $orcamento->numero ?? "#{$orcamento->id}";
        
        switch ($newStatus) {
            case Orcamento::STATUS_APROVADO:
                return "Seu orçamento {$numero} foi aprovado! Você pode acompanhar o progresso do projeto.";
            case Orcamento::STATUS_REJEITADO:
                return "Seu orçamento {$numero} foi rejeitado. Entre em contato para mais informações.";
            case Orcamento::STATUS_QUITADO:
                return "Seu orçamento {$numero} foi quitado. Obrigado pela preferência!";
            default:
                return "O status do seu orçamento {$numero} foi alterado de {$oldStatus} para {$newStatus}.";
        }
    }

    /**
     * Send status change email notification
     */
    private function sendStatusChangeEmail(Orcamento $orcamento, Notification $notification, string $oldStatus, string $newStatus): void
    {
        try {
            if ($orcamento->cliente && $orcamento->cliente->email) {
                Mail::to($orcamento->cliente->email)->send(
                    new \App\Mail\BudgetStatusChanged($orcamento, $notification, $oldStatus, $newStatus)
                );
                
                \Log::info('Status change email sent successfully', [
                    'orcamento_id' => $orcamento->id,
                    'notification_id' => $notification->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'recipient' => $orcamento->cliente->email
                ]);
            } else {
                \Log::warning('Cannot send status change email - no client email', [
                    'orcamento_id' => $orcamento->id,
                    'notification_id' => $notification->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send status change email', [
                'orcamento_id' => $orcamento->id,
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}