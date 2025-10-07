<?php

require_once 'vendor/autoload.php';

use App\Models\PortfolioWork;

// Verificar se o trabalho existe
$work = PortfolioWork::find(1);

if ($work) {
    echo "Trabalho encontrado:\n";
    echo "ID: " . $work->id . "\n";
    echo "Título: " . $work->title . "\n";
    echo "Status: " . $work->status . "\n";
    echo "User ID: " . $work->user_id . "\n";
} else {
    echo "Trabalho com ID 1 não encontrado!\n";
}

// Verificar se o model binding funciona
try {
    $work = PortfolioWork::findOrFail(1);
    echo "\nModel binding funcionou corretamente!\n";
} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    echo "\nErro no model binding: " . $e->getMessage() . "\n";
}