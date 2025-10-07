<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Asset;

echo "Total de assets: " . Asset::count() . "\n\n";

$assets = Asset::all();

foreach ($assets as $asset) {
    echo "ID: " . $asset->id . "\n";
    echo "Nome original: " . $asset->original_name . "\n";
    echo "Nome do arquivo: " . $asset->filename . "\n";
    echo "Caminho: " . $asset->file_path . "\n";
    echo "URL: " . $asset->file_url . "\n";
    echo "Tipo: " . $asset->type . "\n";
    echo "Formato: " . $asset->format . "\n";
    echo "---\n";
}