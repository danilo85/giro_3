<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\NotificationSeeder;
use Database\Seeders\NotificationLogSeeder;
use App\Models\Notification;
use App\Models\NotificationLog;

class SeedNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:seed 
                            {--clear : Limpar notificações existentes antes de criar novas}
                            {--count=25 : Número de notificações a criar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar notificações e logs de teste para desenvolvimento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔔 Iniciando criação de notificações de teste...');
        $this->newLine();

        // Verificar se deve limpar dados existentes
        if ($this->option('clear')) {
            $this->clearExistingData();
        }

        // Mostrar estatísticas antes
        $this->showStatistics('Antes da execução:');

        // Executar seeders
        $this->info('📝 Executando NotificationSeeder...');
        $this->call('db:seed', ['--class' => NotificationSeeder::class]);
        
        $this->info('📊 Executando NotificationLogSeeder...');
        $this->call('db:seed', ['--class' => NotificationLogSeeder::class]);

        // Mostrar estatísticas depois
        $this->newLine();
        $this->showStatistics('Após a execução:');

        $this->newLine();
        $this->info('✅ Notificações de teste criadas com sucesso!');
        $this->info('💡 Acesse /notifications para visualizar as notificações criadas.');

        return Command::SUCCESS;
    }

    /**
     * Limpar dados existentes
     */
    private function clearExistingData(): void
    {
        if ($this->confirm('⚠️  Tem certeza que deseja limpar todas as notificações e logs existentes?')) {
            $this->info('🗑️  Limpando dados existentes...');
            
            $logsDeleted = NotificationLog::count();
            $notificationsDeleted = Notification::count();
            
            NotificationLog::truncate();
            Notification::truncate();
            
            $this->info("   ✓ {$logsDeleted} logs removidos");
            $this->info("   ✓ {$notificationsDeleted} notificações removidas");
            $this->newLine();
        } else {
            $this->info('❌ Operação cancelada pelo usuário.');
            exit(1);
        }
    }

    /**
     * Mostrar estatísticas atuais
     */
    private function showStatistics(string $title): void
    {
        $this->info($title);
        
        $totalNotifications = Notification::count();
        $readNotifications = Notification::whereNotNull('read_at')->count();
        $unreadNotifications = $totalNotifications - $readNotifications;
        
        $totalLogs = NotificationLog::count();
        $pendingLogs = NotificationLog::where('status', NotificationLog::STATUS_PENDING)->count();
        $sentLogs = NotificationLog::where('status', NotificationLog::STATUS_SENT)->count();
        $deliveredLogs = NotificationLog::where('status', NotificationLog::STATUS_DELIVERED)->count();
        $failedLogs = NotificationLog::where('status', NotificationLog::STATUS_FAILED)->count();

        // Estatísticas de notificações
        $this->table(
            ['Tipo', 'Quantidade'],
            [
                ['Total de Notificações', $totalNotifications],
                ['Notificações Lidas', $readNotifications],
                ['Notificações Não Lidas', $unreadNotifications],
            ]
        );

        // Estatísticas de logs
        $this->table(
            ['Status do Log', 'Quantidade'],
            [
                ['Total de Logs', $totalLogs],
                ['Pendentes', $pendingLogs],
                ['Enviados', $sentLogs],
                ['Entregues', $deliveredLogs],
                ['Falharam', $failedLogs],
            ]
        );

        // Estatísticas por canal
        if ($totalLogs > 0) {
            $emailLogs = NotificationLog::where('channel', NotificationLog::CHANNEL_EMAIL)->count();
            $smsLogs = NotificationLog::where('channel', NotificationLog::CHANNEL_SMS)->count();
            $pushLogs = NotificationLog::where('channel', NotificationLog::CHANNEL_PUSH)->count();

            $this->table(
                ['Canal', 'Quantidade'],
                [
                    ['Email', $emailLogs],
                    ['SMS', $smsLogs],
                    ['Push', $pushLogs],
                ]
            );
        }

        $this->newLine();
    }
}