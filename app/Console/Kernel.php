<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Limpar arquivos temporários diariamente às 2:00 AM
        $schedule->command('files:cleanup-expired')->dailyAt('02:00');
        
        // Limpar links compartilhados expirados diariamente às 3:00 AM
        $schedule->command('files:cleanup-expired-links')->dailyAt('03:00');
        
        // Verificar datas de vencimento de pagamentos diariamente às 9:00 AM
        $schedule->command('payments:check-due-dates')->dailyAt('09:00');
        
        // Processar transações recorrentes diariamente às 6:00 AM
        $schedule->command('transactions:process-recurring')->dailyAt('06:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
