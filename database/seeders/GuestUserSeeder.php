<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GuestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário guest se não existir
        User::firstOrCreate(
            ['email' => 'guest@portfolio.local'],
            [
                'name' => 'Visitante',
                'email' => 'guest@portfolio.local',
                'password' => Hash::make('guest123'),
                'email_verified_at' => now(),
            ]
        );
    }
}