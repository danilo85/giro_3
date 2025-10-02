<?php

// Teste direto da aprovação de orçamento
echo "=== TESTE DE APROVAÇÃO DE ORÇAMENTO ===\n";

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Orcamento;
use App\Models\Cliente;

try {
    echo "1. Criando orçamento de teste...\n";
    
    // Buscar um cliente
    $cliente = Cliente::first();
    if (!$cliente) {
        echo "Nenhum cliente encontrado!\n";
        exit(1);
    }
    
    // Criar um orçamento de teste
    $token = \Illuminate\Support\Str::random(32);
    $orcamento = Orcamento::create([
        'cliente_id' => $cliente->id,
        'titulo' => 'Teste Notificação Observer',
        'descricao' => 'Teste de aprovação pública para verificar Observer',
        'valor_total' => 1000.00,
        'status' => 'pendente',
        'token_publico' => $token
    ]);
    
    echo "Orçamento criado: ID {$orcamento->id}, Token: {$token}\n";
    
    echo "\n2. Testando aprovação direta via método aprovar()...\n";
    
    // Limpar logs antes do teste
    file_put_contents('storage/logs/laravel.log', '');
    
    // Testar aprovação direta
    $orcamento->aprovar();
    
    echo "Aprovação concluída! Novo status: {$orcamento->fresh()->status}\n";
    
    // Verificar logs imediatamente
    echo "\n3. Verificando logs após aprovação...\n";
    $logFile = 'storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        if (!empty($logs)) {
            echo "LOGS ENCONTRADOS:\n";
            echo $logs;
        } else {
            echo "NENHUM LOG ENCONTRADO - PROBLEMA IDENTIFICADO!\n";
        }
    } else {
        echo "Arquivo de log não existe.\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}