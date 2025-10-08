<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecipeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * Get the recipes for the category.
     */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'category_id');
    }

    /**
     * Get active recipes for the category.
     */
    public function activeRecipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'category_id')->where('is_active', true);
    }

    /**
     * Scope to get categories with recipe count.
     */
    public function scopeWithRecipeCount($query)
    {
        return $query->withCount('recipes');
    }

    /**
     * Get the category color with fallback.
     */
    public function getColorAttribute($value): string
    {
        return $value ?: '#3B82F6';
    }
}