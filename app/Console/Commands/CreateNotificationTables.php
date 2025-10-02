<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTables extends Command
{
    protected $signature = 'notifications:create-tables';
    protected $description = 'Create notification system tables';

    public function handle()
    {
        $this->info('Creating notification system tables...');

        try {
            // Create notification_preferences table
            if (!Schema::hasTable('notification_preferences')) {
                Schema::create('notification_preferences', function ($table) {
                    $table->uuid('id')->primary();
                    $table->unsignedBigInteger('user_id')->unique();
                    $table->boolean('email_enabled')->default(true);
                    $table->boolean('budget_notifications')->default(true);
                    $table->boolean('payment_notifications')->default(true);
                    $table->json('notification_days')->default('[7, 3, 1]');
                    $table->timestamps();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->index('user_id');
                });
                $this->info('✓ notification_preferences table created');
            } else {
                $this->info('✓ notification_preferences table already exists');
            }

            // Create jobs table
            if (!Schema::hasTable('jobs')) {
                Schema::create('jobs', function ($table) {
                    $table->bigIncrements('id');
                    $table->string('queue')->index();
                    $table->longText('payload');
                    $table->unsignedTinyInteger('attempts');
                    $table->unsignedInteger('reserved_at')->nullable();
                    $table->unsignedInteger('available_at');
                    $table->unsignedInteger('created_at');
                });
                $this->info('✓ jobs table created');
            } else {
                $this->info('✓ jobs table already exists');
            }

            $this->info('All notification tables are ready!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Error creating tables: ' . $e->getMessage());
            return 1;
        }
    }
}