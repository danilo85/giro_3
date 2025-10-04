<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;
use App\Models\Cliente;
use App\Models\User;

class CreateTestBudget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-budget';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test budget for PDF testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Buscar ou criar um usuário
        $user = User::first();
        if (!$user) {
            $this->error('Nenhum usuário encontrado no sistema');
            return;
        }

        // Buscar ou criar um cliente
        $cliente = Cliente::where('user_id', $user->id)->first();
        if (!$cliente) {
            $cliente = Cliente::create([
                'user_id' => $user->id,
                'nome' => 'Cliente Teste',
                'email' => 'teste@exemplo.com',
                'telefone' => '(11) 99999-9999',
                'endereco' => 'Rua Teste, 123',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-567'
            ]);
        }

        // Criar orçamento de teste
        $orcamento = Orcamento::create([
            'cliente_id' => $cliente->id,
            'titulo' => 'Orçamento de Teste para PDF',
            'descricao' => 'Este é um orçamento de teste criado para testar a funcionalidade de geração de PDF. Contém informações detalhadas sobre o projeto proposto.',
            'valor_total' => 2500.00,
            'prazo_dias' => 30,
            'data_orcamento' => now(),
            'data_validade' => now()->addDays(30),
            'status' => 'analisando',
            'observacoes' => 'Observações importantes sobre este orçamento de teste.'
        ]);

        $this->info('Orçamento de teste criado com sucesso!');
        $this->info('ID: ' . $orcamento->id);
        $this->info('Token: ' . $orcamento->token_publico);
        $this->info('URL Pública: http://127.0.0.1:8000/orcamento/' . $orcamento->token_publico);
    }
}
