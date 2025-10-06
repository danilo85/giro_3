<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:process-recurring {--list : List all recurring transactions} {--dry-run : Show what would be processed without creating transactions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring transactions and create new instances based on their frequency';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('list')) {
            return $this->listRecurringTransactions();
        }

        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 Modo de simulação ativado - nenhuma transação será criada');
        }

        $this->info('🔄 Processando transações recorrentes...');
        
        $recurringTransactions = Transaction::where('frequency_type', 'recorrente')
            ->whereNotNull('recurring_type')
            ->get();

        if ($recurringTransactions->isEmpty()) {
            $this->warn('⚠️  Nenhuma transação recorrente encontrada.');
            return 0;
        }

        $processed = 0;
        $created = 0;

        foreach ($recurringTransactions as $transaction) {
            $result = $this->processTransaction($transaction, $dryRun);
            if ($result['processed']) {
                $processed++;
                if ($result['created']) {
                    $created++;
                }
            }
        }

        if ($dryRun) {
            $this->info("📊 Simulação concluída: {$processed} transações seriam processadas, {$created} novas instâncias seriam criadas.");
        } else {
            $this->info("✅ Processamento concluído: {$processed} transações processadas, {$created} novas instâncias criadas.");
        }

        return 0;
    }

    /**
     * List all recurring transactions
     */
    private function listRecurringTransactions()
    {
        $this->info('📋 Listando todas as transações recorrentes...');
        
        $recurringTransactions = Transaction::where('frequency_type', 'recorrente')
            ->whereNotNull('recurring_type')
            ->with(['category', 'bank', 'creditCard'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($recurringTransactions->isEmpty()) {
            $this->warn('⚠️  Nenhuma transação recorrente encontrada.');
            return 0;
        }

        $headers = ['ID', 'Descrição', 'Valor', 'Tipo', 'Frequência', 'Próxima Data', 'Data Final', 'Status'];
        $rows = [];

        foreach ($recurringTransactions as $transaction) {
            $nextDate = $this->calculateNextDate($transaction);
            $endDate = $transaction->recurring_end_date ? 
                Carbon::parse($transaction->recurring_end_date)->format('d/m/Y') : 
                'Sem limite';

            $rows[] = [
                $transaction->id,
                substr($transaction->descricao, 0, 30) . (strlen($transaction->descricao) > 30 ? '...' : ''),
                'R$ ' . number_format($transaction->valor, 2, ',', '.'),
                ucfirst($transaction->tipo),
                $this->getFrequencyLabel($transaction->recurring_type),
                $nextDate ? $nextDate->format('d/m/Y') : 'N/A',
                $endDate,
                $this->shouldCreateNext($transaction) ? '🟢 Ativa' : '🔴 Expirada'
            ];
        }

        $this->table($headers, $rows);
        $this->info("📊 Total: {$recurringTransactions->count()} transações recorrentes encontradas.");

        return 0;
    }

    /**
     * Process a single recurring transaction
     */
    private function processTransaction(Transaction $transaction, bool $dryRun = false): array
    {
        $result = ['processed' => false, 'created' => false];

        if (!$this->shouldCreateNext($transaction)) {
            return $result;
        }

        $nextDate = $this->calculateNextDate($transaction);
        
        if (!$nextDate || $nextDate->isFuture()) {
            return $result;
        }

        $result['processed'] = true;

        // Verificar se já existe uma transação para esta data
        $existingTransaction = Transaction::where('installment_id', $transaction->installment_id ?: $transaction->id)
            ->where('data', $nextDate->format('Y-m-d'))
            ->where('frequency_type', 'recorrente')
            ->first();

        if ($existingTransaction) {
            $this->line("⏭️  Transação já existe para {$nextDate->format('d/m/Y')}: {$transaction->descricao}");
            return $result;
        }

        if ($dryRun) {
            $this->line("🔮 Seria criada: {$transaction->descricao} para {$nextDate->format('d/m/Y')}");
            $result['created'] = true;
            return $result;
        }

        try {
            DB::beginTransaction();

            $newTransaction = $transaction->replicate();
            $newTransaction->data = $nextDate->format('Y-m-d');
            $newTransaction->installment_id = $transaction->installment_id ?: $transaction->id;
            $newTransaction->status = 'pendente'; // Nova instância sempre começa como pendente
            $newTransaction->data_pagamento = null;
            $newTransaction->created_at = now();
            $newTransaction->updated_at = now();
            
            $newTransaction->save();

            DB::commit();

            $this->line("✅ Criada: {$transaction->descricao} para {$nextDate->format('d/m/Y')}");
            
            Log::info('Transação recorrente criada', [
                'original_id' => $transaction->id,
                'new_id' => $newTransaction->id,
                'date' => $nextDate->format('Y-m-d'),
                'description' => $transaction->descricao
            ]);

            $result['created'] = true;

        } catch (\Exception $e) {
            DB::rollback();
            $this->error("❌ Erro ao criar transação: {$e->getMessage()}");
            Log::error('Erro ao processar transação recorrente', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Check if we should create the next instance of this recurring transaction
     */
    private function shouldCreateNext(Transaction $transaction): bool
    {
        // Verificar se a transação tem data final e se já passou
        if ($transaction->recurring_end_date) {
            $endDate = Carbon::parse($transaction->recurring_end_date);
            if ($endDate->isPast()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate the next date for a recurring transaction
     */
    private function calculateNextDate(Transaction $transaction): ?Carbon
    {
        $lastDate = Carbon::parse($transaction->data);
        $today = Carbon::today();

        // Encontrar a última instância criada desta transação recorrente
        $lastInstance = Transaction::where('installment_id', $transaction->installment_id ?: $transaction->id)
            ->where('frequency_type', 'recorrente')
            ->orderBy('data', 'desc')
            ->first();

        if ($lastInstance && $lastInstance->id !== $transaction->id) {
            $lastDate = Carbon::parse($lastInstance->data);
        }

        // Calcular próxima data baseada na frequência
        switch ($transaction->recurring_type) {
            case 'weekly':
                $nextDate = $lastDate->copy()->addWeek();
                break;
            case 'monthly':
                $nextDate = $lastDate->copy()->addMonth();
                break;
            case 'yearly':
                $nextDate = $lastDate->copy()->addYear();
                break;
            default:
                return null;
        }

        // Verificar se a próxima data não ultrapassa a data final
        if ($transaction->recurring_end_date) {
            $endDate = Carbon::parse($transaction->recurring_end_date);
            if ($nextDate->isAfter($endDate)) {
                return null;
            }
        }

        return $nextDate;
    }

    /**
     * Get frequency label in Portuguese
     */
    private function getFrequencyLabel(string $frequency): string
    {
        return match($frequency) {
            'weekly' => 'Semanal',
            'monthly' => 'Mensal',
            'yearly' => 'Anual',
            default => ucfirst($frequency)
        };
    }
}