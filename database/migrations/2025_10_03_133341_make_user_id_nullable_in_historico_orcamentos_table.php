<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('historico_orcamentos', function (Blueprint $table) {
            // Tornar a coluna user_id nullable para permitir aprovação/rejeição pública
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historico_orcamentos', function (Blueprint $table) {
            // Reverter para NOT NULL (cuidado: pode falhar se houver registros com user_id null)
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
