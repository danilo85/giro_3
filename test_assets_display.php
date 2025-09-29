<?php

require_once 'vendor/autoload.php';

// Carrega o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

echo "=== TESTE DE EXIBIÇÃO DE ASSETS ===\n\n";

// Busca todos os assets
$assets = Asset::orderBy('created_at', 'desc')->take(10)->get();

echo "Total de assets encontrados: " . $assets->count() . "\n\n";

foreach ($assets as $asset) {
    echo "Asset ID: {$asset->id}\n";
    echo "Nome: {$asset->original_name}\n";
    echo "Tipo: {$asset->type}\n";
    echo "Formato: {$asset->format}\n";
    echo "Tamanho: {$asset->formatted_size}\n";
    echo "Arquivo existe: " . (Storage::disk('public')->exists($asset->file_path) ? 'SIM' : 'NÃO') . "\n";
    echo "URL do arquivo: {$asset->file_url}\n";
    
    if ($asset->type === 'image') {
        echo "Thumbnail existe: " . (Storage::disk('public')->exists($asset->thumbnail_path) ? 'SIM' : 'NÃO') . "\n";
        echo "URL do thumbnail: {$asset->thumbnail_url}\n";
    }
    
    echo "Criado em: {$asset->created_at}\n";
    echo "---\n\n";
}

// Verifica estrutura de pastas
echo "=== ESTRUTURA DE PASTAS ===\n";
echo "Pasta assets existe: " . (Storage::disk('public')->exists('assets') ? 'SIM' : 'NÃO') . "\n";
echo "Pasta images existe: " . (Storage::disk('public')->exists('assets/images') ? 'SIM' : 'NÃO') . "\n";
echo "Pasta fonts existe: " . (Storage::disk('public')->exists('assets/fonts') ? 'SIM' : 'NÃO') . "\n";
echo "Pasta thumbnails existe: " . (Storage::disk('public')->exists('assets/thumbnails') ? 'SIM' : 'NÃO') . "\n";

// Lista arquivos nas pastas
echo "\n=== ARQUIVOS NAS PASTAS ===\n";
if (Storage::disk('public')->exists('assets/images')) {
    $images = Storage::disk('public')->files('assets/images');
    echo "Imagens (" . count($images) . "): " . implode(', ', array_map('basename', $images)) . "\n";
}

if (Storage::disk('public')->exists('assets/fonts')) {
    $fonts = Storage::disk('public')->files('assets/fonts');
    echo "Fontes (" . count($fonts) . "): " . implode(', ', array_map('basename', $fonts)) . "\n";
}

if (Storage::disk('public')->exists('assets/thumbnails')) {
    $thumbnails = Storage::disk('public')->files('assets/thumbnails');
    echo "Thumbnails (" . count($thumbnails) . "): " . implode(', ', array_map('basename', $thumbnails)) . "\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";
?>