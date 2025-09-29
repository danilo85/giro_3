<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Asset;

$asset = Asset::first();

if ($asset) {
    echo "Asset encontrado: " . $asset->original_name . "\n";
    echo "File URL: " . $asset->file_url . "\n";
    echo "Thumbnail URL: " . $asset->thumbnail_url . "\n";
    echo "File Path: " . $asset->file_path . "\n";
    echo "Thumbnail Path: " . $asset->thumbnail_path . "\n";
} else {
    echo "Nenhum asset encontrado no banco de dados.\n";
}