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
        Schema::table('modelos_propostas', function (Blueprint $table) {
            $table->integer('usos')->default(0)->after('ativo');
            $table->index('usos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modelos_propostas', function (Blueprint $table) {
            $table->dropIndex(['usos']);
            $table->dropColumn('usos');
        });
    }
};
