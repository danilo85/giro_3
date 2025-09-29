<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Asset extends Model
{
    use HasFactory;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'user_id',
        'original_name',
        'stored_name',
        'file_path',
        'thumbnail_path',
        'type',
        'format',
        'mime_type',
        'file_size',
        'metadata',
        'dimensions'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'dimensions' => 'array',
        'file_size' => 'integer'
    ];
    
    protected $appends = [
        'file_url',
        'thumbnail_url',
        'formatted_size',
        'is_image',
        'is_font'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
        
        static::deleting(function ($model) {
            // Remove arquivos físicos ao deletar o registro
            if ($model->file_path && Storage::disk('public')->exists($model->file_path)) {
                Storage::disk('public')->delete($model->file_path);
            }
            if ($model->thumbnail_path && Storage::disk('public')->exists($model->thumbnail_path)) {
                Storage::disk('public')->delete($model->thumbnail_path);
            }
        });
    }
    
    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function tags(): HasMany
    {
        return $this->hasMany(AssetTag::class);
    }
    
    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }
    
    // Accessors
    public function getFileUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
    
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? Storage::disk('public')->url($this->thumbnail_path) : null;
    }
    
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    public function getIsImageAttribute(): bool
    {
        return $this->type === 'image';
    }
    
    public function getIsFontAttribute(): bool
    {
        return $this->type === 'font';
    }
    
    // Scopes
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }
    
    public function scopeFonts($query)
    {
        return $query->where('type', 'font');
    }
    
    public function scopeByFormat($query, $format)
    {
        return $query->where('format', $format);
    }
    
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('original_name', 'LIKE', "%{$term}%")
              ->orWhere('stored_name', 'LIKE', "%{$term}%")
              ->orWhereHas('tags', function ($tagQuery) use ($term) {
                  $tagQuery->where('tag_name', 'LIKE', "%{$term}%");
              });
        });
    }
    
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    // Métodos auxiliares
    public function addTag(string $tagName): void
    {
        AssetTag::create([
            'asset_id' => $this->id,
            'tag_name' => strtolower(trim($tagName))
        ]);
    }
    
    public function addTags(array $tags): void
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }
    
    public function getTagNames(): array
    {
        return $this->tags->pluck('tag_name')->toArray();
    }
    
    public function logDownload(int $userId, string $downloadType, string $ipAddress): void
    {
        DownloadLog::create([
            'user_id' => $userId,
            'asset_id' => $this->id,
            'download_type' => $downloadType,
            'ip_address' => $ipAddress
        ]);
    }
}
