<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SocialAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('Nenhum usuário encontrado. Execute UserSeeder primeiro.');
            return;
        }

        $socialAccounts = [];
        $platforms = ['instagram', 'facebook', 'linkedin', 'tiktok', 'youtube', 'twitter'];
        
        foreach ($users as $user) {
            // Cada usuário terá entre 2 a 4 contas sociais
            $numAccounts = rand(2, 4);
            $selectedPlatforms = array_rand($platforms, $numAccounts);
            
            if (!is_array($selectedPlatforms)) {
                $selectedPlatforms = [$selectedPlatforms];
            }
            
            foreach ($selectedPlatforms as $platformIndex) {
                $platform = $platforms[$platformIndex];
                
                $socialAccounts[] = [
                    'user_id' => $user->id,
                    'provider' => $platform,
                    'provider_id' => rand(100000, 999999999),
                    'provider_token' => 'fake_token_' . $platform . '_' . $user->id,
                    'provider_refresh_token' => rand(0, 1) ? 'fake_refresh_' . $platform . '_' . $user->id : null,
                    'created_at' => now()->subDays(rand(1, 365)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ];
            }
        }
        
        DB::table('social_accounts')->insert($socialAccounts);
        
        $this->command->info('Contas sociais criadas com sucesso!');
        $this->command->info('Total de contas criadas: ' . count($socialAccounts));
    }
    
    private function generateUsername($platform, $name)
    {
        $cleanName = strtolower(str_replace(' ', '', $name));
        $cleanName = preg_replace('/[^a-z0-9]/', '', $cleanName);
        
        $suffixes = ['', '_oficial', '_' . rand(10, 99), '_' . date('Y'), '_studio'];
        $suffix = $suffixes[array_rand($suffixes)];
        
        return $cleanName . $suffix;
    }
}
