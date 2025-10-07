<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar enum para incluir 'archived'
        DB::statement("ALTER TABLE portfolio_works MODIFY COLUMN status ENUM('draft', 'published', 'archived') DEFAULT 'draft'");
        
        // Criar índice para otimizar consultas por status
        Schema::table('portfolio_works', function (Blueprint $table) {
            $table->index('status', 'idx_portfolio_works_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover índice
        Schema::table('portfolio_works', function (Blueprint $table) {
            $table->dropIndex('idx_portfolio_works_status');
        });
        
        // Reverter enum para valores originais
        DB::statement("ALTER TABLE portfolio_works MODIFY COLUMN status ENUM('draft', 'published') DEFAULT 'draft'");
    }
};
