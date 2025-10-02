<?php

use Illuminate\Support\Facades\Route;
use App\Models\NotificationLog;
use App\Models\Notification;
use App\Models\User;

// Temporary route to check and create test notification logs
Route::get('/test-logs', function () {
    // Get first user or create one
    $user = User::first();
    if (!$user) {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }

    // Check existing logs
    $existingLogs = NotificationLog::with('notification')->limit(5)->get();
    
    if ($existingLogs->count() < 2) {
        // Create test notifications and logs
        for ($i = 1; $i <= 2; $i++) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => 'TYPE_BUDGET_APPROVED',
                'title' => "Test Notification $i",
                'message' => "This is test notification message $i",
                'data' => ['test' => true, 'number' => $i],
                'read_at' => null,
            ]);

            NotificationLog::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'notification_id' => $notification->id,
                'channel' => 'email',
                'status' => $i === 1 ? 'sent' : 'delivered',
                'sent_at' => now()->subMinutes($i * 10),
                'delivered_at' => $i === 2 ? now()->subMinutes(5) : null,
                'created_at' => now()->subMinutes($i * 15),
            ]);
        }
    }

    // Get 2 logs to display
    $logs = NotificationLog::with('notification.user')->limit(2)->get();
    
    $output = "<h1>2 Notification Logs</h1>";
    $output .= "<style>body{font-family:Arial;margin:20px;} .log{border:1px solid #ddd;margin:10px 0;padding:15px;border-radius:5px;} .status{padding:3px 8px;border-radius:3px;color:white;} .sent{background:#28a745;} .delivered{background:#007bff;} .failed{background:#dc3545;}</style>";
    
    foreach ($logs as $index => $log) {
        $statusClass = $log->status;
        $output .= "<div class='log'>";
        $output .= "<h3>Log " . ($index + 1) . "</h3>";
        $output .= "<p><strong>ID:</strong> {$log->id}</p>";
        $output .= "<p><strong>Notification:</strong> {$log->notification->title}</p>";
        $output .= "<p><strong>Message:</strong> {$log->notification->message}</p>";
        $output .= "<p><strong>User:</strong> {$log->notification->user->name} ({$log->notification->user->email})</p>";
        $output .= "<p><strong>Channel:</strong> {$log->channel}</p>";
        $output .= "<p><strong>Status:</strong> <span class='status {$statusClass}'>{$log->status}</span></p>";
        $output .= "<p><strong>Created:</strong> {$log->created_at}</p>";
        if ($log->sent_at) {
            $output .= "<p><strong>Sent:</strong> {$log->sent_at}</p>";
        }
        if ($log->delivered_at) {
            $output .= "<p><strong>Delivered:</strong> {$log->delivered_at}</p>";
        }
        if ($log->error_message) {
            $output .= "<p><strong>Error:</strong> {$log->error_message}</p>";
        }
        $output .= "</div>";
    }
    
    return $output;
});