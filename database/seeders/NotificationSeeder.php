<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando notificações de teste...');

        // Pegar o primeiro usuário ou criar um se não existir
        $user = User::first();
        if (!$user) {
            $this->command->error('Nenhum usuário encontrado. Crie um usuário primeiro.');
            return;
        }

        $this->command->info("Criando notificações para o usuário: {$user->name} (ID: {$user->id})");

        // Tipos de notificações disponíveis
        $types = [
            Notification::TYPE_BUDGET_APPROVED,
            Notification::TYPE_BUDGET_REJECTED,
            Notification::TYPE_BUDGET_PAID,
            Notification::TYPE_BUDGET_CANCELLED,
            Notification::TYPE_PAYMENT_DUE,
            Notification::TYPE_PAYMENT_OVERDUE,
        ];

        $notifications = [];
        $count = 0;

        // Criar 25 notificações variadas
        for ($i = 0; $i < 25; $i++) {
            $type = $types[array_rand($types)];
            $isRead = rand(0, 100) < 60; // 60% chance de estar lida
            
            // Gerar datas variadas (últimos 30 dias)
            $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $readAt = $isRead ? $createdAt->copy()->addMinutes(rand(5, 1440)) : null; // Lida entre 5 min e 24h depois

            $notification = $this->createNotificationByType($type, $user->id, $createdAt, $readAt);
            
            if ($notification) {
                $notifications[] = $notification;
                $count++;
            }
        }

        $this->command->info("✓ {$count} notificações criadas com sucesso!");
    }

    /**
     * Criar notificação baseada no tipo
     */
    private function createNotificationByType(string $type, int $userId, Carbon $createdAt, ?Carbon $readAt): ?Notification
    {
        $data = [];
        $title = '';
        $message = '';

        switch ($type) {
            case Notification::TYPE_BUDGET_APPROVED:
                $budgetId = rand(1000, 9999);
                $value = rand(500, 50000);
                $title = 'Orçamento Aprovado';
                $message = "Seu orçamento #{$budgetId} no valor de R$ " . number_format($value, 2, ',', '.') . " foi aprovado pelo cliente.";
                $data = [
                    'budget_id' => $budgetId,
                    'value' => $value,
                    'client_name' => $this->getRandomClientName(),
                    'action_url' => "/orcamentos/{$budgetId}"
                ];
                break;

            case Notification::TYPE_BUDGET_REJECTED:
                $budgetId = rand(1000, 9999);
                $title = 'Orçamento Rejeitado';
                $message = "Seu orçamento #{$budgetId} foi rejeitado pelo cliente.";
                $data = [
                    'budget_id' => $budgetId,
                    'client_name' => $this->getRandomClientName(),
                    'reason' => $this->getRandomRejectionReason(),
                    'action_url' => "/orcamentos/{$budgetId}"
                ];
                break;

            case Notification::TYPE_BUDGET_PAID:
                $budgetId = rand(1000, 9999);
                $value = rand(500, 50000);
                $title = 'Pagamento Recebido';
                $message = "Pagamento de R$ " . number_format($value, 2, ',', '.') . " do orçamento #{$budgetId} foi confirmado.";
                $data = [
                    'budget_id' => $budgetId,
                    'value' => $value,
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'action_url' => "/orcamentos/{$budgetId}"
                ];
                break;

            case Notification::TYPE_BUDGET_CANCELLED:
                $budgetId = rand(1000, 9999);
                $title = 'Orçamento Cancelado';
                $message = "O orçamento #{$budgetId} foi cancelado.";
                $data = [
                    'budget_id' => $budgetId,
                    'client_name' => $this->getRandomClientName(),
                    'action_url' => "/orcamentos/{$budgetId}"
                ];
                break;

            case Notification::TYPE_PAYMENT_DUE:
                $budgetId = rand(1000, 9999);
                $daysUntilDue = rand(1, 7);
                $value = rand(500, 50000);
                $title = 'Pagamento Próximo do Vencimento';
                $message = "O pagamento do orçamento #{$budgetId} vence em {$daysUntilDue} dia(s).";
                $data = [
                    'budget_id' => $budgetId,
                    'value' => $value,
                    'days_until_due' => $daysUntilDue,
                    'due_date' => Carbon::now()->addDays($daysUntilDue)->format('Y-m-d'),
                    'action_url' => "/orcamentos/{$budgetId}"
                ];
                break;

            case Notification::TYPE_PAYMENT_OVERDUE:
                $budgetId = rand(1000, 9999);
                $daysOverdue = rand(1, 30);
                $value = rand(500, 50000);
                $title = 'Pagamento Vencido';
                $message = "O pagamento do orçamento #{$budgetId} está vencido há {$daysOverdue} dia(s).";
                $data = [
                    'budget_id' => $budgetId,
                    'value' => $value,
                    'days_overdue' => $daysOverdue,
                    'due_date' => Carbon::now()->subDays($daysOverdue)->format('Y-m-d'),
                    'action_url' => "/orcamentos/{$budgetId}"
                ];
                break;

            default:
                return null;
        }

        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read_at' => $readAt,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    /**
     * Obter nome de cliente aleatório
     */
    private function getRandomClientName(): string
    {
        $names = [
            'João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa', 'Carlos Ferreira',
            'Lucia Rodrigues', 'Roberto Lima', 'Fernanda Alves', 'Marcos Pereira', 'Juliana Souza',
            'Ricardo Martins', 'Camila Barbosa', 'Eduardo Gomes', 'Patrícia Ribeiro', 'Felipe Carvalho'
        ];
        
        return $names[array_rand($names)];
    }

    /**
     * Obter motivo de rejeição aleatório
     */
    private function getRandomRejectionReason(): string
    {
        $reasons = [
            'Valor acima do orçamento disponível',
            'Prazo de entrega muito longo',
            'Escopo não atende às necessidades',
            'Optou por outro fornecedor',
            'Projeto foi cancelado',
            'Necessita de ajustes no escopo'
        ];
        
        return $reasons[array_rand($reasons)];
    }

    /**
     * Obter método de pagamento aleatório
     */
    private function getRandomPaymentMethod(): string
    {
        $methods = [
            'Transferência Bancária',
            'PIX',
            'Cartão de Crédito',
            'Boleto Bancário',
            'Dinheiro'
        ];
        
        return $methods[array_rand($methods)];
    }
}