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
        // Tabela principal de assets
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('file_path', 500);
            $table->string('thumbnail_path', 500)->nullable();
            $table->enum('type', ['image', 'font']);
            $table->string('format', 10);
            $table->string('mime_type', 100);
            $table->bigInteger('file_size')->unsigned();
            $table->json('metadata')->nullable();
            $table->json('dimensions')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('type');
            $table->index('format');
            $table->index('created_at');
            $table->fullText(['original_name', 'stored_name']);
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Tabela de tags
        Schema::create('asset_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->string('tag_name', 50);
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->index('asset_id');
            $table->index('tag_name');
        });
        
        // Tabela de logs de download
        Schema::create('download_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('asset_id')->nullable();
            $table->enum('download_type', ['single', 'batch', 'zip']);
            $table->string('ip_address', 45);
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('set null');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_logs');
        Schema::dropIfExists('asset_tags');
        Schema::dropIfExists('assets');
    }
};
