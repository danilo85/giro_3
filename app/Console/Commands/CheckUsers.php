<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check existing users in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::select('id', 'name', 'email')->get();
        
        $this->info('Usuários existentes:');
        foreach ($users as $user) {
            $this->line($user->id . ' - ' . $user->name . ' - ' . $user->email);
        }
        
        $this->info('Total de usuários: ' . $users->count());
    }
}
