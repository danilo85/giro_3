<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Asset;
use App\Models\User;

echo "=== Teste de Upload de Fontes ===\n\n";

// Verificar se as pastas existem
$paths = [
    'storage/app/public/assets' => storage_path('app/public/assets'),
    'storage/app/public/assets/fonts' => storage_path('app/public/assets/fonts'),
    'storage/app/public/assets/images' => storage_path('app/public/assets/images'),
    'storage/app/public/assets/thumbnails' => storage_path('app/public/assets/thumbnails'),
    'storage/app/public/assets/thumbnails/images' => storage_path('app/public/assets/thumbnails/images'),
    'public/storage' => public_path('storage')
];

echo "1. Verificando estrutura de pastas:\n";
foreach ($paths as $name => $path) {
    $exists = file_exists($path);
    $writable = $exists ? is_writable($path) : false;
    echo "   {$name}: " . ($exists ? 'EXISTS' : 'NOT EXISTS') . ($writable ? ' (WRITABLE)' : ' (NOT WRITABLE)') . "\n";
    
    if (!$exists) {
        echo "   Criando pasta: {$path}\n";
        mkdir($path, 0755, true);
    }
}

echo "\n2. Verificando link simbólico:\n";
$linkPath = public_path('storage');
$linkExists = is_link($linkPath);
$linkTarget = $linkExists ? readlink($linkPath) : null;
echo "   Link exists: " . ($linkExists ? 'YES' : 'NO') . "\n";
if ($linkExists) {
    echo "   Link target: {$linkTarget}\n";
    echo "   Target exists: " . (file_exists($linkTarget) ? 'YES' : 'NO') . "\n";
}

echo "\n3. Testando Storage disk:\n";
try {
    $disk = Storage::disk('public');
    echo "   Storage disk initialized: YES\n";
    echo "   Assets folder exists: " . ($disk->exists('assets') ? 'YES' : 'NO') . "\n";
    echo "   Fonts folder exists: " . ($disk->exists('assets/fonts') ? 'YES' : 'NO') . "\n";
    echo "   Images folder exists: " . ($disk->exists('assets/images') ? 'YES' : 'NO') . "\n";
    
    // Criar pastas se não existirem
    if (!$disk->exists('assets')) {
        $disk->makeDirectory('assets');
        echo "   Created assets folder\n";
    }
    if (!$disk->exists('assets/fonts')) {
        $disk->makeDirectory('assets/fonts');
        echo "   Created fonts folder\n";
    }
    if (!$disk->exists('assets/images')) {
        $disk->makeDirectory('assets/images');
        echo "   Created images folder\n";
    }
    if (!$disk->exists('assets/thumbnails')) {
        $disk->makeDirectory('assets/thumbnails');
        echo "   Created thumbnails folder\n";
    }
    if (!$disk->exists('assets/thumbnails/images')) {
        $disk->makeDirectory('assets/thumbnails/images');
        echo "   Created thumbnails/images folder\n";
    }
    
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\n4. Verificando usuários:\n";
$user = User::first();
if ($user) {
    echo "   User found: {$user->email} (ID: {$user->id})\n";
} else {
    echo "   No users found\n";
}

echo "\n5. Verificando assets existentes:\n";
$assets = Asset::all();
echo "   Total assets: {$assets->count()}\n";
foreach ($assets as $asset) {
    echo "   - {$asset->name} ({$asset->type}) - User: {$asset->user_id}\n";
    echo "     File: {$asset->file_path} - Exists: " . (Storage::disk('public')->exists($asset->file_path) ? 'YES' : 'NO') . "\n";
    if ($asset->thumbnail_path) {
        echo "     Thumbnail: {$asset->thumbnail_path} - Exists: " . (Storage::disk('public')->exists($asset->thumbnail_path) ? 'YES' : 'NO') . "\n";
    }
}

echo "\n6. Testando tipos MIME aceitos:\n";
$fontMimes = ['font/ttf', 'font/otf', 'application/font-woff', 'application/font-woff2', 'font/woff', 'font/woff2'];
foreach ($fontMimes as $mime) {
    echo "   {$mime}: ACCEPTED\n";
}

echo "\n=== Fim do Teste ===\n";