<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'preparation_method',
        'preparation_time',
        'servings',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'preparation_time' => 'integer',
        'servings' => 'integer',
    ];

    /**
     * Get the user that owns the recipe.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the recipe.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(RecipeCategory::class, 'category_id');
    }

    /**
     * Get the recipe ingredients for the recipe.
     */
    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    /**
     * Get the ingredients for the recipe.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }

    /**
     * Get the full URL for the recipe's image.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    /**
     * Get formatted preparation time.
     */
    public function getFormattedPreparationTimeAttribute(): string
    {
        if ($this->preparation_time < 60) {
            return $this->preparation_time . ' min';
        }
        
        $hours = floor($this->preparation_time / 60);
        $minutes = $this->preparation_time % 60;
        
        if ($minutes > 0) {
            return $hours . 'h ' . $minutes . 'min';
        }
        
        return $hours . 'h';
    }

    /**
     * Get preparation time category.
     */
    public function getPreparationTimeCategoryAttribute(): string
    {
        if ($this->preparation_time <= 30) {
            return 'rápido';
        } elseif ($this->preparation_time <= 60) {
            return 'médio';
        } else {
            return 'demorado';
        }
    }

    /**
     * Scope to get only active recipes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get recipes for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to search recipes by name.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%");
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to filter by preparation time.
     */
    public function scopeByPreparationTime($query, $timeCategory)
    {
        switch ($timeCategory) {
            case 'rápido':
                return $query->where('preparation_time', '<=', 30);
            case 'médio':
                return $query->whereBetween('preparation_time', [31, 60]);
            case 'demorado':
                return $query->where('preparation_time', '>', 60);
            default:
                return $query;
        }
    }

    /**
     * Scope to filter by servings.
     */
    public function scopeByServings($query, $servings)
    {
        return $query->where('servings', $servings);
    }

    /**
     * Toggle the active status of the recipe.
     */
    public function toggleStatus(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    /**
     * Delete the recipe and its image file.
     */
    public function delete()
    {
        // Delete the image file if it exists
        if ($this->image_path && Storage::exists($this->image_path)) {
            Storage::delete($this->image_path);
        }

        return parent::delete();
    }
}