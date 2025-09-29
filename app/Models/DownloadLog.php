<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DownloadLog extends Model
{
    use HasFactory;
    
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'asset_id',
        'download_type',
        'ip_address'
    ];
    
    protected $dates = [
        'created_at'
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
    
    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
    
    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeByAsset($query, $assetId)
    {
        return $query->where('asset_id', $assetId);
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('download_type', $type);
    }
    
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
    
    // Métodos estáticos para estatísticas
    public static function getDownloadStats($userId = null, $days = 30)
    {
        $query = static::recent($days);
        
        if ($userId) {
            $query->byUser($userId);
        }
        
        return [
            'total' => $query->count(),
            'by_type' => $query->selectRaw('download_type, COUNT(*) as count')
                             ->groupBy('download_type')
                             ->pluck('count', 'download_type')
                             ->toArray(),
            'daily' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                           ->groupBy('date')
                           ->orderBy('date')
                           ->pluck('count', 'date')
                           ->toArray()
        ];
    }
}
