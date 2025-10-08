<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'default_unit',
    ];

    /**
     * Get the recipe ingredients for the ingredient.
     */
    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    /**
     * Get the recipes that use this ingredient.
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }

    /**
     * Scope to search ingredients by name.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%");
    }

    /**
     * Scope to get ingredients ordered by name.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name', 'asc');
    }
}