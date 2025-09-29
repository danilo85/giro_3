<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

echo "=== TESTE DE DEBUG PARA UPLOAD DE FONTES ===\n\n";

// 1. Verificar estrutura de pastas
echo "1. Verificando estrutura de pastas:\n";
$publicPath = storage_path('app/public');
echo "Storage path: {$publicPath}\n";
echo "Pasta storage/app/public existe: " . (is_dir($publicPath) ? 'SIM' : 'NÃO') . "\n";

$assetsPath = $publicPath . '/assets';
echo "Pasta assets existe: " . (is_dir($assetsPath) ? 'SIM' : 'NÃO') . "\n";

$fontsPath = $assetsPath . '/fonts';
echo "Pasta fonts existe: " . (is_dir($fontsPath) ? 'SIM' : 'NÃO') . "\n";

$thumbnailsPath = $assetsPath . '/thumbnails';
echo "Pasta thumbnails existe: " . (is_dir($thumbnailsPath) ? 'SIM' : 'NÃO') . "\n";

// 2. Criar pastas se não existirem
echo "\n2. Criando pastas necessárias:\n";
if (!is_dir($assetsPath)) {
    mkdir($assetsPath, 0755, true);
    echo "Pasta assets criada\n";
}

if (!is_dir($fontsPath)) {
    mkdir($fontsPath, 0755, true);
    echo "Pasta fonts criada\n";
}

if (!is_dir($thumbnailsPath)) {
    mkdir($thumbnailsPath, 0755, true);
    echo "Pasta thumbnails criada\n";
}

// 3. Verificar permissões
echo "\n3. Verificando permissões:\n";
echo "Assets writable: " . (is_writable($assetsPath) ? 'SIM' : 'NÃO') . "\n";
echo "Fonts writable: " . (is_writable($fontsPath) ? 'SIM' : 'NÃO') . "\n";
echo "Thumbnails writable: " . (is_writable($thumbnailsPath) ? 'SIM' : 'NÃO') . "\n";

// 4. Verificar link simbólico
echo "\n4. Verificando link simbólico:\n";
$publicStoragePath = public_path('storage');
echo "Link público existe: " . (file_exists($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";
echo "É um link: " . (is_link($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";
if (is_link($publicStoragePath)) {
    echo "Aponta para: " . readlink($publicStoragePath) . "\n";
}

// 5. Testar Storage facade
echo "\n5. Testando Storage facade:\n";
try {
    $testFile = 'test.txt';
    Storage::disk('public')->put($testFile, 'teste');
    echo "Arquivo de teste criado: SIM\n";
    
    $exists = Storage::disk('public')->exists($testFile);
    echo "Arquivo existe: " . ($exists ? 'SIM' : 'NÃO') . "\n";
    
    $url = Storage::disk('public')->url($testFile);
    echo "URL gerada: {$url}\n";
    
    Storage::disk('public')->delete($testFile);
    echo "Arquivo de teste removido\n";
} catch (Exception $e) {
    echo "ERRO no Storage: " . $e->getMessage() . "\n";
}

// 6. Simular upload de fonte
echo "\n6. Simulando upload de fonte:\n";
try {
    // Criar um arquivo de fonte temporário
    $tempFontPath = sys_get_temp_dir() . '/test_font.ttf';
    file_put_contents($tempFontPath, 'fake font content for testing');
    
    // Simular UploadedFile
    $uploadedFile = new UploadedFile(
        $tempFontPath,
        'test_font.ttf',
        'font/ttf',
        null,
        true
    );
    
    echo "Arquivo temporário criado: {$tempFontPath}\n";
    echo "Nome original: " . $uploadedFile->getClientOriginalName() . "\n";
    echo "MIME type: " . $uploadedFile->getMimeType() . "\n";
    echo "Extensão: " . $uploadedFile->getClientOriginalExtension() . "\n";
    
    // Testar armazenamento
    $storedPath = $uploadedFile->store('assets/fonts', 'public');
    echo "Arquivo armazenado em: {$storedPath}\n";
    
    $fullPath = Storage::disk('public')->path($storedPath);
    echo "Caminho completo: {$fullPath}\n";
    echo "Arquivo existe fisicamente: " . (file_exists($fullPath) ? 'SIM' : 'NÃO') . "\n";
    
    // Limpar
    Storage::disk('public')->delete($storedPath);
    unlink($tempFontPath);
    echo "Arquivos de teste removidos\n";
    
} catch (Exception $e) {
    echo "ERRO no upload simulado: " . $e->getMessage() . "\n";
}

// 7. Verificar usuários
echo "\n7. Verificando usuários:\n";
$userCount = User::count();
echo "Total de usuários: {$userCount}\n";

if ($userCount > 0) {
    $user = User::first();
    echo "Primeiro usuário: {$user->email}\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";