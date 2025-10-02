<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\NotificationLog;
use Carbon\Carbon;

class NotificationLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando logs de notificações...');

        // Buscar todas as notificações existentes
        $notifications = Notification::all();
        
        if ($notifications->isEmpty()) {
            $this->command->error('Nenhuma notificação encontrada. Execute o NotificationSeeder primeiro.');
            return;
        }

        $this->command->info("Criando logs para {$notifications->count()} notificações...");

        $totalLogs = 0;

        foreach ($notifications as $notification) {
            // Gerar entre 1 e 3 logs por notificação
            $logCount = rand(1, 3);
            
            for ($i = 0; $i < $logCount; $i++) {
                $log = $this->createLogForNotification($notification, $i);
                if ($log) {
                    $totalLogs++;
                }
            }
        }

        $this->command->info("✓ {$totalLogs} logs de notificação criados com sucesso!");
    }

    /**
     * Criar log para uma notificação
     */
    private function createLogForNotification(Notification $notification, int $index): ?NotificationLog
    {
        // Canais disponíveis
        $channels = [
            NotificationLog::CHANNEL_EMAIL,
            NotificationLog::CHANNEL_SMS,
            NotificationLog::CHANNEL_PUSH,
        ];

        // Status disponíveis com probabilidades diferentes
        $statusOptions = [
            NotificationLog::STATUS_DELIVERED => 50, // 50% chance
            NotificationLog::STATUS_SENT => 25,      // 25% chance
            NotificationLog::STATUS_FAILED => 15,    // 15% chance
            NotificationLog::STATUS_PENDING => 10,   // 10% chance
        ];

        $channel = $channels[array_rand($channels)];
        $status = $this->getRandomStatus($statusOptions);

        // Calcular timestamps baseados na data da notificação
        $baseTime = $notification->created_at;
        $createdAt = $baseTime->copy()->addMinutes(rand(1, 30)); // Log criado 1-30 min após notificação
        
        $sentAt = null;
        $deliveredAt = null;
        $errorMessage = null;

        // Definir timestamps baseados no status
        switch ($status) {
            case NotificationLog::STATUS_PENDING:
                // Apenas created_at
                break;

            case NotificationLog::STATUS_SENT:
                $sentAt = $createdAt->copy()->addMinutes(rand(1, 5));
                break;

            case NotificationLog::STATUS_DELIVERED:
                $sentAt = $createdAt->copy()->addMinutes(rand(1, 5));
                $deliveredAt = $sentAt->copy()->addMinutes(rand(1, 10));
                break;

            case NotificationLog::STATUS_FAILED:
                $sentAt = $createdAt->copy()->addMinutes(rand(1, 5));
                $errorMessage = $this->getRandomErrorMessage($channel);
                break;
        }

        return NotificationLog::create([
            'notification_id' => $notification->id,
            'channel' => $channel,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => $sentAt,
            'delivered_at' => $deliveredAt,
            'created_at' => $createdAt,
        ]);
    }

    /**
     * Obter status aleatório baseado em probabilidades
     */
    private function getRandomStatus(array $statusOptions): string
    {
        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($statusOptions as $status => $probability) {
            $cumulative += $probability;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        // Fallback para delivered
        return NotificationLog::STATUS_DELIVERED;
    }

    /**
     * Obter mensagem de erro aleatória baseada no canal
     */
    private function getRandomErrorMessage(string $channel): string
    {
        $errors = [
            NotificationLog::CHANNEL_EMAIL => [
                'Email address not found',
                'SMTP server timeout',
                'Invalid email format',
                'Mailbox full',
                'Email blocked by spam filter',
                'Authentication failed',
                'Connection refused by server',
            ],
            NotificationLog::CHANNEL_SMS => [
                'Invalid phone number',
                'SMS gateway timeout',
                'Insufficient credits',
                'Number not reachable',
                'Message too long',
                'Carrier rejected message',
                'Network error',
            ],
            NotificationLog::CHANNEL_PUSH => [
                'Device token invalid',
                'Push service unavailable',
                'Certificate expired',
                'Payload too large',
                'Rate limit exceeded',
                'Device not registered',
                'Network timeout',
            ],
        ];

        $channelErrors = $errors[$channel] ?? ['Unknown error'];
        return $channelErrors[array_rand($channelErrors)];
    }
}