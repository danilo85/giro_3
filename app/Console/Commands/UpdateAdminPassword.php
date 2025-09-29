<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-admin-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update admin user password to admin123';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = \App\Models\User::where('email', 'admin@giro.com')->first();
        
        if ($admin) {
            $admin->password = \Hash::make('admin123');
            $admin->save();
            $this->info('Senha do usuário admin atualizada para: admin123');
        } else {
            $this->error('Usuário admin não encontrado!');
        }
    }
}
