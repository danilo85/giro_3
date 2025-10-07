<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioWorkLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'portfolio_work_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function portfolioWork(): BelongsTo
    {
        return $this->belongsTo(PortfolioWork::class);
    }
}
