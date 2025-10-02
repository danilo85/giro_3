<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
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
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the notification logs for this notification.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    /**
     * Get the notification logs for this notification.
     * Alias for logs() method to maintain compatibility.
     */
    public function notificationLogs(): HasMany
    {
        return $this->logs();
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Check if the notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if the notification is unread.
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Scope to get only unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope to get only read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get notifications for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Notification types constants
    const TYPE_BUDGET_APPROVED = 'budget_approved';
    const TYPE_BUDGET_REJECTED = 'budget_rejected';
    const TYPE_BUDGET_PAID = 'budget_paid';
    const TYPE_BUDGET_CANCELLED = 'budget_cancelled';
    const TYPE_PAYMENT_DUE = 'payment_due';
    const TYPE_PAYMENT_OVERDUE = 'payment_overdue';

    /**
     * Get all available notification types.
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_BUDGET_APPROVED => 'Orçamento Aprovado',
            self::TYPE_BUDGET_REJECTED => 'Orçamento Rejeitado',
            self::TYPE_BUDGET_PAID => 'Orçamento Pago',
            self::TYPE_BUDGET_CANCELLED => 'Orçamento Cancelado',
            self::TYPE_PAYMENT_DUE => 'Pagamento Próximo do Vencimento',
            self::TYPE_PAYMENT_OVERDUE => 'Pagamento Vencido',
        ];
    }

    /**
     * Get all available notification type labels.
     * Alias for getTypes() method to maintain compatibility.
     */
    public static function getTypeLabels(): array
    {
        return self::getTypes();
    }

    /**
     * Get the type label.
     */
    public function getTypeLabel(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get the CSS classes for the type badge.
     */
    public function getTypeBadgeClass(): string
    {
        $classes = [
            self::TYPE_BUDGET_APPROVED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::TYPE_BUDGET_REJECTED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            self::TYPE_BUDGET_PAID => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::TYPE_BUDGET_CANCELLED => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
            self::TYPE_PAYMENT_DUE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::TYPE_PAYMENT_OVERDUE => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        ];

        return $classes[$this->type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
    }

    /**
     * Get the action URL for the notification.
     */
    public function getActionUrl(): ?string
    {
        // Check if action_url is set in the data array
        if (isset($this->data['action_url'])) {
            return $this->data['action_url'];
        }

        // Generate default URLs based on notification type
        switch ($this->type) {
            case self::TYPE_BUDGET_APPROVED:
            case self::TYPE_BUDGET_REJECTED:
            case self::TYPE_BUDGET_PAID:
            case self::TYPE_BUDGET_CANCELLED:
                if (isset($this->data['budget_id'])) {
                    return route('budgets.show', $this->data['budget_id']);
                }
                break;
            case self::TYPE_PAYMENT_DUE:
            case self::TYPE_PAYMENT_OVERDUE:
                if (isset($this->data['payment_id'])) {
                    return route('payments.show', $this->data['payment_id']);
                }
                break;
        }

        return null;
    }

    /**
     * Get the action text for the notification.
     */
    public function getActionText(): string
    {
        // Check if action_text is set in the data array
        if (isset($this->data['action_text'])) {
            return $this->data['action_text'];
        }

        // Generate default action text based on notification type
        switch ($this->type) {
            case self::TYPE_BUDGET_APPROVED:
            case self::TYPE_BUDGET_REJECTED:
            case self::TYPE_BUDGET_PAID:
            case self::TYPE_BUDGET_CANCELLED:
                return 'Ver Orçamento';
            case self::TYPE_PAYMENT_DUE:
            case self::TYPE_PAYMENT_OVERDUE:
                return 'Ver Pagamento';
            default:
                return 'Ver Detalhes';
        }
    }
}