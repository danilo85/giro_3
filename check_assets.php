<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asset;

try {
    echo "Verificando assets no banco de dados...\n";
    
    $assets = Asset::select('id', 'original_name', 'file_path')
                   ->take(10)
                   ->get();
    
    if ($assets->count() > 0) {
        echo "Assets encontrados:\n";
        foreach ($assets as $asset) {
            echo "ID: {$asset->id} - Nome: {$asset->original_name} - Arquivo: {$asset->file_path}\n";
        }
    } else {
        echo "Nenhum asset encontrado no banco de dados.\n";
    }
    
    echo "\nTotal de assets: " . Asset::count() . "\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}