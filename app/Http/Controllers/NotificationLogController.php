<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationLogController extends Controller
{
    /**
     * Display notification logs
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Get logs for user's notifications
        $logs = NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['notification'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('channel'), function ($query, $channel) {
                return $query->where('channel', $channel);
            })
            ->when($request->get('notification_id'), function ($query, $notificationId) {
                return $query->where('notification_id', $notificationId);
            })
            ->latest()
            ->paginate(20);

        // Get filter options
        $statuses = NotificationLog::getStatusLabels();
        $channels = NotificationLog::getChannelLabels();
        
        // Get user's notifications for filter
        $notifications = $user->notifications()
            ->select('id', 'title', 'type')
            ->latest()
            ->limit(50)
            ->get();

        // Get statistics
        $stats = [
            'total' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'sent' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', NotificationLog::STATUS_SENT)->count(),
            'delivered' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', NotificationLog::STATUS_DELIVERED)->count(),
            'failed' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', NotificationLog::STATUS_FAILED)->count(),
        ];

        return view('notifications.logs.index', compact('logs', 'statuses', 'channels', 'notifications', 'stats'));
    }

    /**
     * Show specific notification log
     */
    public function show(NotificationLog $log): View
    {
        // Check if user owns this notification log
        if ($log->notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $log->load(['notification']);

        return view('notifications.logs.show', compact('log'));
    }

    /**
     * Get logs for a specific notification (AJAX)
     */
    public function getLogsForNotification(Notification $notification): JsonResponse
    {
        // Check if user owns this notification
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $logs = $notification->notificationLogs()
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'channel' => $log->channel,
                    'channel_label' => $log->getChannelLabel(),
                    'status' => $log->status,
                    'status_label' => $log->getStatusLabel(),
                    'error_message' => $log->error_message,
                    'sent_at' => $log->sent_at ? $log->sent_at->format('d/m/Y H:i:s') : null,
                    'delivered_at' => $log->delivered_at ? $log->delivered_at->format('d/m/Y H:i:s') : null,
                    'created_at' => $log->created_at->format('d/m/Y H:i:s'),
                    'is_pending' => $log->isPending(),
                    'is_sent' => $log->isSent(),
                    'is_delivered' => $log->isDelivered(),
                    'is_failed' => $log->isFailed(),
                ];
            });

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    /**
     * Retry failed notification log
     */
    public function retry(NotificationLog $log): JsonResponse
    {
        // Check if user owns this notification log
        if ($log->notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Only allow retry for failed logs
        if (!$log->isFailed()) {
            return response()->json([
                'success' => false,
                'message' => 'Only failed notifications can be retried'
            ], 400);
        }

        try {
            // Reset log status to pending
            $log->update([
                'status' => NotificationLog::STATUS_PENDING,
                'error_message' => null,
                'sent_at' => null,
                'delivered_at' => null
            ]);

            // Trigger notification resend based on channel
            $this->resendNotification($log);

            return response()->json([
                'success' => true,
                'message' => 'Notification retry initiated successfully'
            ]);

        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retry notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification log statistics
     */
    public function getStats(): JsonResponse
    {
        $user = auth()->user();
        
        $stats = [
            'total' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'pending' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', NotificationLog::STATUS_PENDING)->count(),
            'sent' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', NotificationLog::STATUS_SENT)->count(),
            'delivered' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', NotificationLog::STATUS_DELIVERED)->count(),
            'failed' => NotificationLog::whereHas('notification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', NotificationLog::STATUS_FAILED)->count(),
        ];

        // Calculate success rate
        $totalSent = $stats['sent'] + $stats['delivered'];
        $successRate = $stats['total'] > 0 ? round(($totalSent / $stats['total']) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'stats' => array_merge($stats, ['success_rate' => $successRate])
        ]);
    }

    /**
     * Resend notification based on channel
     */
    private function resendNotification(NotificationLog $log): void
    {
        $notification = $log->notification;
        
        switch ($log->channel) {
            case NotificationLog::CHANNEL_EMAIL:
                $this->resendEmailNotification($notification, $log);
                break;
                
            case NotificationLog::CHANNEL_SMS:
                // TODO: Implement SMS resending when SMS functionality is added
                throw new \Exception('SMS resending not implemented yet');
                
            case NotificationLog::CHANNEL_PUSH:
                // TODO: Implement push notification resending when push functionality is added
                throw new \Exception('Push notification resending not implemented yet');
                
            default:
                throw new \Exception('Unknown notification channel: ' . $log->channel);
        }
    }

    /**
     * Resend email notification
     */
    private function resendEmailNotification(Notification $notification, NotificationLog $log): void
    {
        $user = $notification->user;
        
        // Determine which email to send based on notification type
        switch ($notification->type) {
            case Notification::TYPE_BUDGET_APPROVED:
            case Notification::TYPE_BUDGET_REJECTED:
            case Notification::TYPE_BUDGET_PAID:
            case Notification::TYPE_BUDGET_CANCELLED:
                if (isset($notification->data['orcamento_id'])) {
                    $orcamento = \App\Models\Orcamento::find($notification->data['orcamento_id']);
                    if ($orcamento) {
                        $oldStatus = $notification->data['old_status'] ?? 'unknown';
                        $newStatus = $notification->data['new_status'] ?? $notification->type;
                        
                        \Mail::to($user->email)->send(
                            new \App\Mail\BudgetStatusChanged($orcamento, $notification, $oldStatus, $newStatus)
                        );
                    }
                }
                break;
                
            case Notification::TYPE_PAYMENT_DUE:
            case Notification::TYPE_PAYMENT_OVERDUE:
                if (isset($notification->data['orcamento_id'])) {
                    $orcamento = \App\Models\Orcamento::find($notification->data['orcamento_id']);
                    if ($orcamento) {
                        $daysUntilDue = $notification->data['days_until_due'] ?? 0;
                        
                        \Mail::to($user->email)->send(
                            new \App\Mail\PaymentDueAlert($orcamento, $notification, $daysUntilDue)
                        );
                    }
                }
                break;
                
            default:
                throw new \Exception('Unknown notification type for email resending: ' . $notification->type);
        }
        
        $log->markAsSent();
    }
}