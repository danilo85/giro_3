<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AssetTag extends Model
{
    use HasFactory;
    
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'asset_id',
        'tag_name'
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
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
    
    // Scopes
    public function scopeByTag($query, $tagName)
    {
        return $query->where('tag_name', strtolower(trim($tagName)));
    }
    
    public function scopePopular($query, $limit = 10)
    {
        return $query->selectRaw('tag_name, COUNT(*) as count')
                    ->groupBy('tag_name')
                    ->orderByDesc('count')
                    ->limit($limit);
    }
}
