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
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->boolean('system_enabled')->default(true)->after('email_enabled');
            $table->boolean('budget_status_change')->default(true)->after('budget_notifications');
            $table->boolean('budget_approved')->default(true)->after('budget_status_change');
            $table->boolean('budget_rejected')->default(true)->after('budget_approved');
            $table->boolean('budget_paid')->default(true)->after('budget_rejected');
            $table->boolean('payment_due_alerts')->default(true)->after('payment_notifications');
            $table->json('payment_due_days')->nullable()->after('payment_due_alerts');
            $table->boolean('payment_overdue_alerts')->default(true)->after('payment_due_days');
            $table->string('email_frequency')->default('immediate')->after('notification_days');
            $table->time('email_time')->default('09:00')->after('email_frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->dropColumn([
                'system_enabled',
                'budget_status_change',
                'budget_approved',
                'budget_rejected',
                'budget_paid',
                'payment_due_alerts',
                'payment_due_days',
                'payment_overdue_alerts',
                'email_frequency',
                'email_time'
            ]);
        });
    }
};
