<?php

require_once 'vendor/autoload.php';

// Carrega o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo "=== TESTE DE LOGIN E ASSETS ===\n\n";

// Busca o primeiro usuário
$user = User::first();
if (!$user) {
    echo "Nenhum usuário encontrado. Criando usuário de teste...\n";
    $user = User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => Hash::make('password')
    ]);
    echo "Usuário criado: {$user->email}\n";
}

echo "Usuário encontrado: {$user->email}\n";

// Simula login
Auth::login($user);
echo "Login realizado com sucesso!\n";
echo "Usuário autenticado: " . (Auth::check() ? 'SIM' : 'NÃO') . "\n\n";

// Busca assets do usuário
echo "=== ASSETS DO USUÁRIO ===\n";
$userAssets = Asset::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
echo "Assets do usuário {$user->email}: {$userAssets->count()}\n\n";

foreach ($userAssets as $asset) {
    echo "Asset: {$asset->original_name}\n";
    echo "Tipo: {$asset->type}\n";
    echo "URL: {$asset->file_url}\n";
    if ($asset->type === 'image') {
        echo "Thumbnail: {$asset->thumbnail_url}\n";
    }
    echo "---\n";
}

// Busca todos os assets (para debug)
echo "\n=== TODOS OS ASSETS ===\n";
$allAssets = Asset::orderBy('created_at', 'desc')->get();
echo "Total de assets no sistema: {$allAssets->count()}\n\n";

foreach ($allAssets as $asset) {
    echo "Asset: {$asset->original_name} (User ID: {$asset->user_id})\n";
    echo "Tipo: {$asset->type}\n";
    echo "URL: {$asset->file_url}\n";
    if ($asset->type === 'image') {
        echo "Thumbnail: {$asset->thumbnail_url}\n";
    }
    echo "---\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";
?>