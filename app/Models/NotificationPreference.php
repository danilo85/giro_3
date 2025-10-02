<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'email_enabled',
        'system_enabled',
        'budget_notifications',
        'budget_status_change',
        'budget_approved',
        'budget_rejected',
        'budget_paid',
        'payment_notifications',
        'payment_due_alerts',
        'payment_due_days',
        'payment_overdue_alerts',
        'notification_days',
        'email_frequency',
        'email_time',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'system_enabled' => 'boolean',
        'budget_notifications' => 'boolean',
        'budget_status_change' => 'boolean',
        'budget_approved' => 'boolean',
        'budget_rejected' => 'boolean',
        'budget_paid' => 'boolean',
        'payment_notifications' => 'boolean',
        'payment_due_alerts' => 'boolean',
        'payment_due_days' => 'array',
        'payment_overdue_alerts' => 'boolean',
        'notification_days' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the user that owns the notification preferences.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if email notifications are enabled.
     */
    public function isEmailEnabled(): bool
    {
        return $this->email_enabled;
    }

    /**
     * Check if budget notifications are enabled.
     */
    public function areBudgetNotificationsEnabled(): bool
    {
        return $this->budget_notifications;
    }

    /**
     * Check if payment notifications are enabled.
     */
    public function arePaymentNotificationsEnabled(): bool
    {
        return $this->payment_notifications;
    }

    /**
     * Get the notification days as an array.
     */
    public function getNotificationDays(): array
    {
        return $this->notification_days ?? [7, 3, 1];
    }

    /**
     * Check if notifications should be sent for a specific number of days.
     */
    public function shouldNotifyForDays(int $days): bool
    {
        return in_array($days, $this->getNotificationDays());
    }

    /**
     * Enable email notifications.
     */
    public function enableEmail(): void
    {
        $this->update(['email_enabled' => true]);
    }

    /**
     * Disable email notifications.
     */
    public function disableEmail(): void
    {
        $this->update(['email_enabled' => false]);
    }

    /**
     * Enable budget notifications.
     */
    public function enableBudgetNotifications(): void
    {
        $this->update(['budget_notifications' => true]);
    }

    /**
     * Disable budget notifications.
     */
    public function disableBudgetNotifications(): void
    {
        $this->update(['budget_notifications' => false]);
    }

    /**
     * Enable payment notifications.
     */
    public function enablePaymentNotifications(): void
    {
        $this->update(['payment_notifications' => true]);
    }

    /**
     * Disable payment notifications.
     */
    public function disablePaymentNotifications(): void
    {
        $this->update(['payment_notifications' => false]);
    }

    /**
     * Update notification days.
     */
    public function updateNotificationDays(array $days): void
    {
        $this->update(['notification_days' => $days]);
    }

    /**
     * Get or create notification preferences for a user.
     */
    public static function getOrCreateForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'email_enabled' => true,
                'budget_notifications' => true,
                'payment_notifications' => true,
                'notification_days' => [7, 3, 1],
            ]
        );
    }

    /**
     * Scope to get preferences for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get default notification preferences.
     */
    public static function getDefaults(): array
    {
        return [
            'email_enabled' => true,
            'system_enabled' => true,
            'budget_notifications' => true,
            'budget_status_change' => true,
            'budget_approved' => true,
            'budget_rejected' => true,
            'budget_paid' => true,
            'payment_notifications' => true,
            'payment_due_alerts' => true,
            'payment_due_days' => [7, 3, 1],
            'payment_overdue_alerts' => true,
            'notification_days' => [7, 3, 1],
            'email_frequency' => 'immediate',
            'email_time' => '09:00:00',
        ];
    }
}