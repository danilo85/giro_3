<?php

// Simular uma requisição HTTP para testar a aprovação pública
$token = 'test-token-123'; // Token de teste
$url = "http://127.0.0.1:8000/orcamento/{$token}/aprovar";

// Dados para a requisição PATCH
$data = [];

// Configurar contexto para requisição HTTP
$options = [
    'http' => [
        'header' => [
            "Content-Type: application/json",
            "Accept: application/json",
            "X-Requested-With: XMLHttpRequest"
        ],
        'method' => 'PATCH',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);

echo "Testando aprovação pública...\n";
echo "URL: {$url}\n";
echo "Método: PATCH\n\n";

// Fazer a requisição
$result = file_get_contents($url, false, $context);

if ($result === false) {
    echo "Erro na requisição!\n";
    $error = error_get_last();
    echo "Erro: " . $error['message'] . "\n";
} else {
    echo "Resposta recebida:\n";
    echo $result . "\n";
}

echo "\nVerificando logs...\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    if (!empty($logs)) {
        echo "Logs encontrados:\n";
        echo $logs;
    } else {
        echo "Nenhum log encontrado.\n";
    }
} else {
    echo "Arquivo de log não existe.\n";
}