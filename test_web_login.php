<?php

// Script para testar login via web e verificar assets
echo "=== TESTE DE LOGIN VIA WEB ===\n\n";

// Primeiro, vamos obter o token CSRF da página de login
$loginPageResponse = file_get_contents('http://localhost:8000/login');
if ($loginPageResponse === false) {
    echo "Erro ao acessar página de login\n";
    exit(1);
}

// Extrai o token CSRF
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPageResponse, $matches);
if (empty($matches[1])) {
    echo "Token CSRF não encontrado\n";
    exit(1);
}

$csrfToken = $matches[1];
echo "Token CSRF obtido: {$csrfToken}\n";

// Extrai o cookie de sessão
preg_match('/Set-Cookie: ([^;]+)/', implode('\n', $http_response_header ?? []), $cookieMatches);
$sessionCookie = $cookieMatches[1] ?? '';

echo "Cookie de sessão: {$sessionCookie}\n";

// Dados de login
$loginData = [
    '_token' => $csrfToken,
    'email' => 'admin@giro.com',
    'password' => 'password'
];

// Configura o contexto para a requisição POST
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: ' . $sessionCookie,
            'X-CSRF-TOKEN: ' . $csrfToken
        ],
        'content' => http_build_query($loginData)
    ]
]);

// Faz o login
echo "\nTentando fazer login...\n";
$loginResponse = file_get_contents('http://localhost:8000/login', false, $context);

if ($loginResponse === false) {
    echo "Erro no login\n";
    var_dump($http_response_header);
} else {
    echo "Login realizado com sucesso!\n";
    
    // Verifica se foi redirecionado (status 302)
    $responseHeaders = $http_response_header ?? [];
    foreach ($responseHeaders as $header) {
        if (strpos($header, 'Location:') !== false) {
            echo "Redirecionado para: {$header}\n";
        }
    }
}

echo "\n=== TESTE CONCLUÍDO ===\n";
?>