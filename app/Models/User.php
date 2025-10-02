<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_admin',
        'is_active',
        'admin_approved',
        'approved_by',
        'approved_at',
        'last_login_at',
        'last_login_ip',
        'is_online',
        'last_activity_at',
        'cpf_cnpj',
        'assinatura_digital',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'tiktok_url',
        'whatsapp_url',
        'website_url',
        'telefone_whatsapp',
        'email_extra',
        'biografia',
        'profissao',
        'rodape_image',
        'qrcode_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'admin_approved' => 'boolean',
        'approved_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_online' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the social accounts for the user.
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the banks for the user.
     */
    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    /**
     * Get the credit cards for the user.
     */
    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }

    /**
     * Get the categories for the user.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the logos for the user.
     */
    public function logos()
    {
        return $this->hasMany(UserLogo::class);
    }

    /**
     * Get the files for the user.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the file activity logs for the user.
     */
    public function fileActivityLogs()
    {
        return $this->hasMany(FileActivityLog::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the notification preferences for the user.
     */
    public function notificationPreference()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    /**
     * Get unread notifications for the user.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * Get the count of unread notifications.
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Get the user who approved this user.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get users approved by this user.
     */
    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Check if user is approved by admin.
     */
    public function isApproved()
    {
        return $this->admin_approved;
    }

    /**
     * Check if user can login (is active and approved if required).
     */
    public function canLogin()
    {
        if (!$this->is_active) {
            return false;
        }

        // Get admin approval setting
        $adminApprovalRequired = \App\Http\Controllers\SettingsController::get('system', 'admin_approval', false);

        if ($adminApprovalRequired && !$this->admin_approved) {
            return false;
        }

        return true;
    }

    /**
     * Check if email verification is required based on system settings.
     */
    public function shouldVerifyEmail()
    {
        // Get email verification setting from system settings
        $emailVerificationRequired = \App\Http\Controllers\SettingsController::get('system', 'email_verification', false);
        
        return $emailVerificationRequired;
    }

    /**
     * Override hasVerifiedEmail to check system settings.
     */
    public function hasVerifiedEmail()
    {
        // If email verification is not required, consider email as verified
        if (!$this->shouldVerifyEmail()) {
            return true;
        }

        return !is_null($this->email_verified_at);
    }

    /**
     * Override markEmailAsVerified to check system settings.
     */
    public function markEmailAsVerified()
    {
        // Only mark as verified if verification is required
        if ($this->shouldVerifyEmail()) {
            return $this->forceFill([
                'email_verified_at' => $this->freshTimestamp(),
            ])->save();
        }

        return true;
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Generate default avatar using initials
        $initials = collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->join('');
            
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&color=3B82F6&background=EBF4FF&size=128';
    }

    /**
     * Get the user's role display name.
     */
    public function getRoleAttribute()
    {
        return $this->is_admin ? 'admin' : 'standard';
    }

    /**
     * Update user's last activity.
     */
    public function updateLastActivity()
    {
        $this->update([
            'last_activity_at' => now(),
            'is_online' => true,
        ]);
    }

    /**
     * Get the user's signature URL.
     */
    public function getAssinaturaUrlAttribute()
    {
        if ($this->assinatura_digital) {
            return asset('storage/' . $this->assinatura_digital);
        }
        return null;
    }

    /**
     * Get the user's rodape image URL.
     */
    public function getRodapeImageUrlAttribute()
    {
        if ($this->rodape_image) {
            return asset('storage/' . $this->rodape_image);
        }
        return null;
    }

    /**
     * Get the user's QR code image URL.
     */
    public function getQrcodeImageUrlAttribute()
    {
        if ($this->qrcode_image) {
            return asset('storage/' . $this->qrcode_image);
        }
        return null;
    }

    /**
     * Get logo by type.
     */
    public function getLogoByType($tipo)
    {
        return $this->logos()->where('tipo', $tipo)->first();
    }

    /**
     * Get all logos grouped by type.
     */
    public function getLogosGroupedAttribute()
    {
        return $this->logos->groupBy('tipo');
    }

    /**
     * Get social media URLs as an array.
     */
    public function getSocialMediaAttribute()
    {
        return [
            'facebook' => $this->facebook_url,
            'instagram' => $this->instagram_url,
            'twitter' => $this->twitter_url,
            'linkedin' => $this->linkedin_url,
            'youtube' => $this->youtube_url,
            'tiktok' => $this->tiktok_url,
            'whatsapp' => $this->whatsapp_url,
            'website' => $this->website_url,
        ];
    }

    /**
     * Check if user has any social media configured.
     */
    public function hasSocialMedia()
    {
        return !empty(array_filter($this->social_media));
    }

    /**
     * Get social media platforms with configured URLs.
     */
    public function getConfiguredSocialMediaAttribute()
    {
        return array_filter($this->social_media);
    }
}
