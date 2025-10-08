<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['searchIngredients']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Recipe::with(['category', 'user'])
            ->forUser(Auth::id())
            ->latest();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
            // Se status for vazio (""), mostra todas (ativas e inativas)
        } else {
            // Por padrão, mostra apenas as ativas se não houver parâmetro de status na URL
            $query->active();
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by preparation time
        if ($request->filled('time')) {
            $query->byPreparationTime($request->time);
        }

        // Filter by servings
        if ($request->filled('servings')) {
            $query->byServings($request->servings);
        }

        $recipes = $query->get();
        $categories = RecipeCategory::withRecipeCount()->get();

        return view('recipes.index', compact('recipes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = RecipeCategory::orderBy('name')->get();
        return view('recipes.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipeRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('recipes', 'public');
            }

            // Create recipe
            $recipe = Recipe::create([
                'user_id' => Auth::id(),
                'category_id' => $validated['recipe_category_id'],
                'name' => $validated['title'],
                'description' => $validated['description'],
                'preparation_method' => $validated['preparation_method'],
                'preparation_time' => $validated['preparation_time'],
                'servings' => $validated['servings'],
                'image_path' => $imagePath,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Add ingredients
            foreach ($validated['ingredients'] as $ingredientData) {
                // Find or create ingredient
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => $ingredientData['name']],
                    ['default_unit' => $ingredientData['unit']]
                );

                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredientData['quantity'],
                    'unit' => $ingredientData['unit'],
                ]);
            }

            DB::commit();

            return redirect()->route('recipes.index')
                ->with('success', 'Receita criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating recipe: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()
                ->with('error', 'Erro ao criar receita. Tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $this->authorize('view', $recipe);
        
        $recipe->load(['category', 'recipeIngredients.ingredient', 'user']);
        
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        $this->authorize('update', $recipe);
        
        $recipe->load(['recipeIngredients.ingredient']);
        $categories = RecipeCategory::orderBy('name')->get();
        
        return view('recipes.edit', compact('recipe', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Handle image upload if new image is provided
            $imagePath = $recipe->image_path;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($recipe->image_path && Storage::disk('public')->exists($recipe->image_path)) {
                    Storage::disk('public')->delete($recipe->image_path);
                }
                
                // Store new image
                $imagePath = $request->file('image')->store('recipes', 'public');
            }

            // Update recipe
            $recipe->update([
                'category_id' => $validated['recipe_category_id'],
                'name' => $validated['title'],
                'description' => $validated['description'],
                'preparation_method' => $validated['preparation_method'],
                'preparation_time' => $validated['preparation_time'],
                'servings' => $validated['servings'],
                'image_path' => $imagePath,
                'is_active' => $validated['is_active'] ?? $recipe->is_active,
            ]);

            // Delete existing ingredients
            $recipe->recipeIngredients()->delete();

            // Add new ingredients
            foreach ($validated['ingredients'] as $ingredientData) {
                // Find or create ingredient
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => $ingredientData['name']],
                    ['default_unit' => $ingredientData['unit']]
                );

                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredientData['quantity'],
                    'unit' => $ingredientData['unit'],
                ]);
            }

            DB::commit();

            return redirect()->route('recipes.index')
                ->with('success', 'Receita atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating recipe: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erro ao atualizar receita. Tente novamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $this->authorize('delete', $recipe);

        try {
            $recipe->delete(); // This will also delete the image file via the model's delete method
            
            return redirect()->route('recipes.index')
                ->with('success', 'Receita excluída com sucesso!');

        } catch (\Exception $e) {
            Log::error('Error deleting recipe: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir receita. Tente novamente.');
        }
    }

    /**
     * Toggle the active status of a recipe.
     */
    public function toggleStatus(Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        try {
            $recipe->toggleStatus();

            return response()->json([
                'success' => true,
                'is_active' => $recipe->is_active,
                'message' => 'Status atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling recipe status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status.'
            ], 500);
        }
    }

    /**
     * Search ingredients for autocomplete.
     */
    public function searchIngredients(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        $ingredients = Ingredient::search($query)
            ->ordered()
            ->limit($limit)
            ->get(['id', 'name', 'default_unit']);

        return response()->json(
            $ingredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'default_unit' => $ingredient->default_unit,
                ];
            })
        );
    }

    /**
     * Upload recipe image.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        try {
            $path = $request->file('image')->store('recipes', 'public');
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $url,
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading recipe image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload da imagem.'
            ], 500);
        }
    }
}