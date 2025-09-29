<?php

// Simular upload de fonte via HTTP
$url = 'http://localhost:8000/assets/upload';

// Criar um arquivo de fonte temporário para teste
$fontContent = base64_decode('AAEAAAAOAIAAAwBgT1MvMj3hSQEAAADsAAAAVmNtYXDQEhm3AAABRAAAAUpjdnQgBkFGRgAAAZAAAAA+ZnBnbYoKeDsAAALQAAAJkWdhc3AAAAAQAAAMU');
$tempFontFile = tempnam(sys_get_temp_dir(), 'test_font') . '.ttf';
file_put_contents($tempFontFile, $fontContent);

echo "=== Teste de Upload de Fonte via HTTP ===\n\n";

echo "1. Arquivo de teste criado: {$tempFontFile}\n";
echo "   Tamanho: " . filesize($tempFontFile) . " bytes\n";
echo "   MIME type: " . mime_content_type($tempFontFile) . "\n\n";

// Obter token CSRF
echo "2. Obtendo token CSRF...\n";
$loginPage = file_get_contents('http://localhost:8000/login');
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "   ERRO: Não foi possível obter o token CSRF\n";
    exit(1);
}

echo "   Token CSRF obtido: {$csrfToken}\n\n";

// Simular login
echo "3. Fazendo login...\n";
$loginData = [
    '_token' => $csrfToken,
    'email' => 'admin@giro.com',
    'password' => 'password'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($loginData)
    ]
]);

$loginResponse = file_get_contents('http://localhost:8000/login', false, $context);

// Extrair cookies da resposta
$cookies = [];
foreach ($http_response_header as $header) {
    if (strpos($header, 'Set-Cookie:') === 0) {
        $cookie = substr($header, 12);
        $cookies[] = explode(';', $cookie)[0];
    }
}

$cookieHeader = 'Cookie: ' . implode('; ', $cookies);
echo "   Cookies obtidos: " . implode('; ', $cookies) . "\n\n";

// Testar upload de fonte
echo "4. Testando upload de fonte...\n";

$boundary = '----WebKitFormBoundary' . uniqid();
$postData = '';

// Adicionar token CSRF
$postData .= "--{$boundary}\r\n";
$postData .= "Content-Disposition: form-data; name=\"_token\"\r\n\r\n";
$postData .= "{$csrfToken}\r\n";

// Adicionar arquivo
$postData .= "--{$boundary}\r\n";
$postData .= "Content-Disposition: form-data; name=\"files[]\"; filename=\"test-font.ttf\"\r\n";
$postData .= "Content-Type: font/ttf\r\n\r\n";
$postData .= file_get_contents($tempFontFile) . "\r\n";
$postData .= "--{$boundary}--\r\n";

$uploadContext = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            "Content-Type: multipart/form-data; boundary={$boundary}",
            $cookieHeader
        ],
        'content' => $postData
    ]
]);

try {
    $uploadResponse = file_get_contents('http://localhost:8000/assets/upload', false, $uploadContext);
    echo "   Upload realizado com sucesso!\n";
    echo "   Resposta: " . substr($uploadResponse, 0, 200) . "...\n";
} catch (Exception $e) {
    echo "   ERRO no upload: " . $e->getMessage() . "\n";
    
    // Verificar headers de resposta
    if (isset($http_response_header)) {
        echo "   Headers de resposta:\n";
        foreach ($http_response_header as $header) {
            echo "     {$header}\n";
        }
    }
}

// Limpar arquivo temporário
unlink($tempFontFile);

echo "\n=== Fim do Teste ===\n";