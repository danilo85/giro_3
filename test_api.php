<?php

// Teste simples da API de ingredientes
$url = 'http://127.0.0.1:8002/utilidades/receitas/ingredients/search?q=frango';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'Accept: application/json',
            'User-Agent: Test Script'
        ]
    ]
]);

echo "Testando URL: $url\n";

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "Erro ao fazer a requisição\n";
    print_r($http_response_header);
} else {
    echo "Resposta recebida:\n";
    echo $response . "\n";
    
    // Tentar decodificar JSON
    $data = json_decode($response, true);
    if ($data !== null) {
        echo "JSON válido! Dados:\n";
        print_r($data);
    } else {
        echo "Resposta não é JSON válido\n";
    }
}