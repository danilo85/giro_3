<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    /**
     * Display the notification dashboard
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Get notifications with pagination
        $notifications = $user->notifications()
            ->with(['notificationLogs'])
            ->when($request->get('type'), function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->get('read') !== null, function ($query) use ($request) {
                return $request->get('read') === '1' 
                    ? $query->read() 
                    : $query->unread();
            })
            ->latest()
            ->paginate(20);

        // Get notification statistics
        $stats = [
            'total' => $user->notifications()->count(),
            'unread' => $user->unreadNotifications()->count(),
            'read' => $user->notifications()->read()->count(),
            'today' => $user->notifications()->whereDate('created_at', today())->count(),
        ];

        // Get notification types for filter
        $types = Notification::getTypeLabels();

        return view('notifications.index', compact('notifications', 'stats', 'types'));
    }

    /**
     * Get notifications for API/AJAX requests
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $notifications = $user->notifications()
            ->when($request->get('type'), function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->get('unread_only'), function ($query) {
                return $query->unread();
            })
            ->latest()
            ->limit($request->get('limit', 10))
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'is_read' => !is_null($notification->read_at),
                ];
            });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        // Check if user owns this notification
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'unread_count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Notification $notification): JsonResponse
    {
        // Check if user owns this notification
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread',
            'unread_count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = auth()->user();
        
        $user->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
            'unread_count' => 0
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        // Check if user owns this notification
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully',
            'unread_count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Bulk actions on notifications
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id'
        ]);

        $user = auth()->user();
        $notificationIds = $request->notification_ids;
        
        // Ensure user owns all notifications
        $notifications = $user->notifications()->whereIn('id', $notificationIds);
        
        if ($notifications->count() !== count($notificationIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Some notifications not found or unauthorized'
            ], 403);
        }

        switch ($request->action) {
            case 'mark_read':
                $notifications->update(['read_at' => now()]);
                $message = 'Notifications marked as read';
                break;
                
            case 'mark_unread':
                $notifications->update(['read_at' => null]);
                $message = 'Notifications marked as unread';
                break;
                
            case 'delete':
                $notifications->delete();
                $message = 'Notifications deleted';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Show notification preferences
     */
    public function preferences(): View
    {
        $user = auth()->user();
        $preferences = $user->notificationPreference;
        
        if (!$preferences) {
            $preferences = new NotificationPreference(NotificationPreference::getDefaults());
            $preferences->user_id = $user->id;
        }
        
        return view('notifications.preferences', compact('preferences'));
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $request->validate([
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
            'payment_due_days.*' => 'integer|min:0|max:30',
            'payment_overdue_alerts' => 'boolean',
            'notification_days' => 'array',
            'notification_days.*' => 'integer|min:0|max:30',
            'email_frequency' => 'in:immediate,daily,weekly',
            'email_time' => 'date_format:H:i:s'
        ]);

        $user = auth()->user();
        $preferences = $user->notificationPreference ?? new NotificationPreference(['user_id' => $user->id]);
        
        $preferences->fill([
            'email_enabled' => $request->boolean('email_enabled'),
            'system_enabled' => $request->boolean('system_enabled'),
            'budget_notifications' => $request->boolean('budget_notifications'),
            'budget_status_change' => $request->boolean('budget_status_change'),
            'budget_approved' => $request->boolean('budget_approved'),
            'budget_rejected' => $request->boolean('budget_rejected'),
            'budget_paid' => $request->boolean('budget_paid'),
            'payment_notifications' => $request->boolean('payment_notifications'),
            'payment_due_alerts' => $request->boolean('payment_due_alerts'),
            'payment_due_days' => $request->payment_due_days ?? [],
            'payment_overdue_alerts' => $request->boolean('payment_overdue_alerts'),
            'notification_days' => $request->notification_days ?? [],
            'email_frequency' => $request->email_frequency ?? 'immediate',
            'email_time' => $request->email_time ?? '09:00:00'
        ]);
        
        $preferences->save();

        return redirect()->route('notifications.preferences')
            ->with('success', 'Preferências de notificação atualizadas com sucesso!');
    }

    /**
     * Get unread notification count for header/badge
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado',
                'count' => 0
            ], 401);
        }
        
        $count = $user->unreadNotifications()->count();
        
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Show notification details
     */
    public function show(Notification $notification): View
    {
        // Check if user owns this notification
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Mark as read if not already read
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        // Load related data based on notification type
        $relatedData = null;
        if ($notification->data) {
            if (isset($notification->data['orcamento_id'])) {
                $relatedData = \App\Models\Orcamento::find($notification->data['orcamento_id']);
            } elseif (isset($notification->data['pagamento_id'])) {
                $relatedData = \App\Models\Pagamento::find($notification->data['pagamento_id']);
            }
        }

        return view('notifications.show', compact('notification', 'relatedData'));
    }
}