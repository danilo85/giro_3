<?php

// Simular upload de fonte via HTTP
$url = 'http://localhost:8000/assets/upload';

// Primeiro, obter o token CSRF
$loginUrl = 'http://localhost:8000/login';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$loginPage = curl_exec($ch);

// Extrair token CSRF
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? '';

echo "CSRF Token: {$csrfToken}\n";

// Fazer login
$loginData = [
    '_token' => $csrfToken,
    'email' => 'admin@giro.com',
    'password' => 'password'
];

curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$loginResult = curl_exec($ch);

echo "Login realizado\n";

// Criar arquivo de fonte temporário
$fontContent = file_get_contents('https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2');
if (!$fontContent) {
    // Se não conseguir baixar, criar um arquivo fake
    $fontContent = 'fake font content for testing';
}

$tempFile = tempnam(sys_get_temp_dir(), 'font_test');
file_put_contents($tempFile, $fontContent);

echo "Arquivo temporário criado: {$tempFile}\n";
echo "Tamanho do arquivo: " . filesize($tempFile) . " bytes\n";

// Preparar dados para upload
$postData = [
    '_token' => $csrfToken,
    'files[]' => new CURLFile($tempFile, 'font/woff2', 'test-font.woff2'),
    'tags' => 'teste,fonte',
    'auto_organize' => '1',
    'generate_thumbnails' => '1'
];

// Fazer upload
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-CSRF-TOKEN: ' . $csrfToken,
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "\nResposta HTTP: {$httpCode}\n";
echo "Resposta: {$response}\n";

// Limpar
curl_close($ch);
unlink($tempFile);
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}

echo "\nTeste concluído.\n";