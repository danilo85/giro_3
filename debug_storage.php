<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

echo "Debug do Storage...\n\n";

$assets = Asset::all();

foreach ($assets as $asset) {
    $filePath = $asset->file_path;
    
    echo "Asset: " . $asset->original_name . "\n";
    echo "Caminho no DB: " . $filePath . "\n";
    echo "URL gerada: " . $asset->file_url . "\n";
    
    // Verificar diferentes formas
    $fullPath = storage_path('app/public/' . $filePath);
    echo "Caminho completo: " . $fullPath . "\n";
    echo "Arquivo existe (file_exists): " . (file_exists($fullPath) ? 'SIM' : 'NÃO') . "\n";
    echo "Storage exists: " . (Storage::disk('public')->exists($filePath) ? 'SIM' : 'NÃO') . "\n";
    
    echo "---\n";
}

// Verificar configuração do disco
echo "Configuração do disco público:\n";
echo "Root: " . Storage::disk('public')->getAdapter()->getPathPrefix() . "\n";
echo "URL: " . Storage::disk('public')->url('test') . "\n";