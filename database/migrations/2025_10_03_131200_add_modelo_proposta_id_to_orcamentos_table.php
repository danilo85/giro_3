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
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->unsignedBigInteger('modelo_proposta_id')->nullable()->after('cliente_id');
            $table->foreign('modelo_proposta_id')->references('id')->on('modelos_propostas')->onDelete('set null');
            $table->index('modelo_proposta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropForeign(['modelo_proposta_id']);
            $table->dropIndex(['modelo_proposta_id']);
            $table->dropColumn('modelo_proposta_id');
        });
    }
};
