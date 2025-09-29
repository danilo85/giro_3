<?php

require_once 'vendor/autoload.php';

// Carregar o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Verificar e atualizar o usuário admin
$user = User::where('email', 'admin@giro.com')->first();

if ($user) {
    echo "Usuário encontrado: {$user->email}\n";
    echo "Email verificado antes: " . ($user->email_verified_at ? $user->email_verified_at : 'Não') . "\n";
    
    $user->email_verified_at = now();
    $user->save();
    
    echo "Email verificado agora: {$user->email_verified_at}\n";
    echo "Email do usuário admin foi verificado com sucesso!\n";
} else {
    echo "Usuário admin@giro.com não encontrado.\n";
    
    // Listar todos os usuários
    $users = User::all();
    echo "Usuários disponíveis:\n";
    foreach ($users as $u) {
        echo "- {$u->email} (verificado: " . ($u->email_verified_at ? 'Sim' : 'Não') . ")\n";
    }
}