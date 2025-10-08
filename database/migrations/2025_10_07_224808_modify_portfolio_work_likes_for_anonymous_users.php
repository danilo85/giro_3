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
        Schema::table('portfolio_work_likes', function (Blueprint $table) {
            // Drop the unique constraint first
            $table->dropUnique('unique_user_work_like');
        });
        
        Schema::table('portfolio_work_likes', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('portfolio_work_likes', function (Blueprint $table) {
            // Modify user_id to be a string to support anonymous IDs
            $table->string('user_id')->change();
        });
        
        Schema::table('portfolio_work_likes', function (Blueprint $table) {
            // Re-add the unique constraint
            $table->unique(['user_id', 'portfolio_work_id'], 'unique_user_work_like');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_work_likes', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('unique_user_work_like');
            
            // Change user_id back to unsignedBigInteger
            $table->unsignedBigInteger('user_id')->change();
            
            // Re-add the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Re-add the unique constraint
            $table->unique(['user_id', 'portfolio_work_id'], 'unique_user_work_like');
        });
    }
};
