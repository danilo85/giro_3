<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

echo "Verificando assets com arquivos físicos ausentes...\n\n";

$assets = Asset::all();

foreach ($assets as $asset) {
    $filePath = $asset->file_path;
    $fileExists = Storage::disk('public')->exists($filePath);
    
    echo "Asset: " . $asset->original_name . "\n";
    echo "Caminho: " . $filePath . "\n";
    echo "Existe: " . ($fileExists ? 'SIM' : 'NÃO') . "\n";
    
    if (!$fileExists) {
        echo "REMOVENDO asset órfão...\n";
        $asset->delete();
        echo "Asset removido com sucesso!\n";
    }
    
    echo "---\n";
}

echo "Verificação concluída!\n";