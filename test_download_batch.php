<?php

// Teste simples para verificar a rota de download em lote
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simular uma requisição POST para a rota de download em lote
$request = Illuminate\Http\Request::create('/assets/download-batch', 'POST', [
    'asset_ids' => [1, 2], // IDs de exemplo
    '_token' => 'test-token'
]);

// Adicionar headers necessários
$request->headers->set('Accept', 'application/json');
$request->headers->set('Content-Type', 'application/json');

try {
    $response = $kernel->handle($request);
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Content: " . $response->getContent() . "\n";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

$kernel->terminate($request, $response);