<?php

namespace App\Services;

use App\Models\File;
use App\Models\TempFileExtension;
use App\Models\TempFileNotification;
use App\Models\TempFileSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TempFileService
{
    /**
     * Set expiration for a temporary file
     */
    public function setExpiration(File $file, Carbon $expiresAt, User $user = null, string $reason = null)
    {
        return $this->setExpirationWithBaseDate($file, $expiresAt, null, $user, $reason);
    }
    
    /**
     * Set expiration for a temporary file with custom base date for validation
     */
    public function setExpirationWithBaseDate(File $file, Carbon $expiresAt, Carbon $baseDate = null, User $user = null, string $reason = null)
    {
        $settings = TempFileSetting::getSettings();
        
        // Validate expiry date
        if (!$settings->isValidExpiryDate($expiresAt, $baseDate)) {
            throw new \InvalidArgumentException('Invalid expiry date. Must be within allowed range.');
        }
        
        $oldExpiresAt = $file->expires_at;
        
        // Update file expiration
        $file->update([
            'expires_at' => $expiresAt,
            'is_temporary' => true
        ]);
        
        // Log the extension if this is an update
        if ($oldExpiresAt && $user) {
            TempFileExtension::create([
                'file_id' => $file->id,
                'old_expires_at' => $oldExpiresAt,
                'new_expires_at' => $expiresAt,
                'reason' => $reason,
                'extended_by' => $user->id,
                'created_at' => now()
            ]);
        }
        
        return $file;
    }
    
    /**
     * Extend file expiration
     */
    public function extendExpiration(File $file, int $additionalDays, User $user, string $reason = null)
    {
        if (!$file->is_temporary || !$file->expires_at) {
            throw new \InvalidArgumentException('File is not temporary or has no expiration date.');
        }
        
        $newExpiresAt = $file->expires_at->addDays($additionalDays);
        
        return $this->setExpirationWithBaseDate($file, $newExpiresAt, $file->expires_at, $user, $reason);
    }
    
    /**
     * Convert permanent file to temporary
     */
    public function convertToTemporary(File $file, int $expiryDays = null)
    {
        $settings = TempFileSetting::getSettings();
        $expiryDays = $expiryDays ?? $settings->default_expiry_days;
        
        $expiresAt = now()->addDays($expiryDays);
        
        return $this->setExpiration($file, $expiresAt);
    }
    
    /**
     * Convert temporary file to permanent
     */
    public function convertToPermanent(File $file)
    {
        $file->update([
            'is_temporary' => false,
            'expires_at' => null
        ]);
        
        // Remove all related notifications
        $file->tempFileNotifications()->delete();
        
        return $file;
    }
    
    /**
     * Get files expiring soon
     */
    public function getFilesExpiringSoon(int $hours = 24)
    {
        return File::expiringSoon($hours)->with(['user', 'category'])->get();
    }
    
    /**
     * Get expired files
     */
    public function getExpiredFiles()
    {
        return File::expired()->with(['user', 'category'])->get();
    }
    
    /**
     * Send expiration warnings
     */
    public function sendExpirationWarnings()
    {
        $notificationsSent = 0;
        
        // Send 24-hour warnings
        $files24h = $this->getFilesExpiringSoon(24);
        foreach ($files24h as $file) {
            $this->sendNotification($file, TempFileNotification::TYPE_24H_WARNING);
            $notificationsSent++;
        }
        
        // Send 1-hour warnings
        $files1h = $this->getFilesExpiringSoon(1);
        foreach ($files1h as $file) {
            $this->sendNotification($file, TempFileNotification::TYPE_1H_WARNING);
            $notificationsSent++;
        }
        
        Log::info('Expiration warnings sent', [
            '24h_warnings' => $files24h->count(),
            '1h_warnings' => $files1h->count(),
            'total_notifications' => $notificationsSent
        ]);
        
        return [
            'notifications_sent' => $notificationsSent,
            '24h_warnings' => $files24h->count(),
            '1h_warnings' => $files1h->count()
        ];
    }
    
    /**
     * Send notification for file
     */
    protected function sendNotification(File $file, string $type)
    {
        // Check if notification already sent
        $existingNotification = TempFileNotification::where('file_id', $file->id)
            ->where('notification_type', $type)
            ->whereNotNull('sent_at')
            ->first();
            
        if ($existingNotification) {
            return;
        }
        
        TempFileNotification::create([
            'file_id' => $file->id,
            'notification_type' => $type,
            'sent_at' => now(),
            'is_read' => false,
            'created_at' => now()
        ]);
    }
    
    /**
     * Clean up expired files
     */
    public function cleanupExpiredFiles()
    {
        $expiredFiles = $this->getExpiredFiles();
        $deletedCount = 0;
        $freedSpace = 0;
        $errors = [];
        
        foreach ($expiredFiles as $file) {
            try {
                // Send expired notification
                $this->sendNotification($file, TempFileNotification::TYPE_EXPIRED);
                
                // Calculate freed space before deletion
                $freedSpace += $file->file_size ?? 0;
                
                // Delete physical file
                if (Storage::exists($file->file_path)) {
                    Storage::delete($file->file_path);
                }
                
                // Delete database record
                $file->delete();
                $deletedCount++;
                
            } catch (\Exception $e) {
                $errors[] = [
                    'file_id' => $file->id,
                    'error' => $e->getMessage()
                ];
                Log::error('Failed to delete expired file', [
                    'file_id' => $file->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::info('Expired files cleanup completed', [
            'deleted_count' => $deletedCount,
            'freed_space' => $freedSpace,
            'errors_count' => count($errors),
            'errors' => $errors
        ]);
        
        return [
            'deleted_count' => $deletedCount,
            'freed_space' => $this->formatBytes($freedSpace),
            'errors' => $errors
        ];
    }
    
    /**
     * Get file statistics
     */
    public function getStatistics()
    {
        $totalSizeTemporary = File::temporary()->sum('file_size');
        $totalSizeExpired = File::expired()->sum('file_size');
        
        return [
            'total_temporary_files' => File::temporary()->count(),
            'files_expiring_24h' => File::expiringSoon(24)->count(),
            'files_expiring_1h' => File::expiringSoon(1)->count(),
            'expiring_soon' => File::expiringSoon(24)->count(), // Alias for compatibility
            'expired_files' => File::expired()->count(),
            'total_size_temporary' => $totalSizeTemporary,
            'total_size_expired' => $totalSizeExpired,
            'total_size_formatted' => $this->formatBytes($totalSizeTemporary + $totalSizeExpired)
        ];
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Get user notifications
     */
    public function getUserNotifications(User $user, bool $unreadOnly = false)
    {
        $query = TempFileNotification::whereHas('file', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('file');
        
        if ($unreadOnly) {
            $query->unread();
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Mark user notifications as read
     */
    public function markNotificationsAsRead(User $user, array $notificationIds = null)
    {
        $query = TempFileNotification::whereHas('file', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        
        if ($notificationIds) {
            $query->whereIn('id', $notificationIds);
        }
        
        return $query->update(['is_read' => true]);
    }
}