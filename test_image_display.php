<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

echo "=== Teste de Exibição de Imagens ===\n\n";

// Verificar se existem assets
$totalAssets = Asset::count();
echo "Total de assets no banco: {$totalAssets}\n\n";

if ($totalAssets > 0) {
    // Buscar alguns assets de imagem
    $imageAssets = Asset::where('type', 'image')->take(3)->get();
    
    echo "Assets de imagem encontrados: " . $imageAssets->count() . "\n\n";
    
    foreach ($imageAssets as $asset) {
        echo "--- Asset: {$asset->original_name} ---\n";
        echo "ID: {$asset->id}\n";
        echo "Tipo: {$asset->type}\n";
        echo "File Path: {$asset->file_path}\n";
        echo "Thumbnail Path: {$asset->thumbnail_path}\n";
        echo "File URL: {$asset->file_url}\n";
        echo "Thumbnail URL: {$asset->thumbnail_url}\n";
        
        // Verificar se os arquivos existem fisicamente
        $fileExists = Storage::disk('public')->exists($asset->file_path);
        $thumbnailExists = $asset->thumbnail_path ? Storage::disk('public')->exists($asset->thumbnail_path) : false;
        
        echo "Arquivo existe: " . ($fileExists ? 'SIM' : 'NÃO') . "\n";
        echo "Thumbnail existe: " . ($thumbnailExists ? 'SIM' : 'NÃO') . "\n";
        echo "\n";
    }
    
    // Verificar assets de fonte
    $fontAssets = Asset::where('type', 'font')->take(3)->get();
    echo "Assets de fonte encontrados: " . $fontAssets->count() . "\n\n";
    
    foreach ($fontAssets as $asset) {
        echo "--- Fonte: {$asset->original_name} ---\n";
        echo "File Path: {$asset->file_path}\n";
        echo "File URL: {$asset->file_url}\n";
        
        $fileExists = Storage::disk('public')->exists($asset->file_path);
        echo "Arquivo existe: " . ($fileExists ? 'SIM' : 'NÃO') . "\n";
        echo "\n";
    }
} else {
    echo "Nenhum asset encontrado. Vamos verificar a estrutura de pastas:\n\n";
    
    $directories = [
        'assets',
        'assets/images', 
        'assets/fonts',
        'assets/thumbnails',
        'assets/thumbnails/images'
    ];
    
    foreach ($directories as $dir) {
        $exists = Storage::disk('public')->exists($dir);
        echo "Pasta {$dir}: " . ($exists ? 'EXISTE' : 'NÃO EXISTE') . "\n";
    }
}

echo "\n=== Fim do Teste ===\n";