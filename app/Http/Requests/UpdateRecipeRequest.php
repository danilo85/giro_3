<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'preparation_time' => 'required|integer|min:1|max:1440', // máximo 24 horas
            'servings' => 'required|integer|min:1|max:100',
            'preparation_method' => 'required|string|max:10000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'recipe_category_id' => 'required|exists:recipe_categories,id',
            'is_active' => 'boolean',
            
            // Validação dos ingredientes
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'ingredients.*.unit' => 'required|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título da receita é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            
            'preparation_time.required' => 'O tempo de preparo é obrigatório.',
            'preparation_time.integer' => 'O tempo de preparo deve ser um número inteiro.',
            'preparation_time.min' => 'O tempo de preparo deve ser de pelo menos 1 minuto.',
            'preparation_time.max' => 'O tempo de preparo não pode ser maior que 1440 minutos (24 horas).',
            
            'servings.required' => 'O número de porções é obrigatório.',
            'servings.integer' => 'O número de porções deve ser um número inteiro.',
            'servings.min' => 'Deve servir pelo menos 1 pessoa.',
            'servings.max' => 'Não pode servir mais de 100 pessoas.',
            
            'preparation_method.required' => 'As instruções de preparo são obrigatórias.',
            'preparation_method.max' => 'As instruções não podem ter mais de 10000 caracteres.',
            
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'image.max' => 'A imagem não pode ser maior que 2MB.',
            
            'recipe_category_id.required' => 'A categoria é obrigatória.',
            'recipe_category_id.exists' => 'A categoria selecionada não existe.',
            
            'ingredients.required' => 'Pelo menos um ingrediente é obrigatório.',
            'ingredients.array' => 'Os ingredientes devem ser fornecidos em formato de lista.',
            'ingredients.min' => 'Pelo menos um ingrediente é obrigatório.',
            
            'ingredients.*.name.required' => 'O nome do ingrediente é obrigatório.',
            'ingredients.*.name.max' => 'O nome do ingrediente não pode ter mais de 255 caracteres.',
            
            'ingredients.*.quantity.required' => 'A quantidade do ingrediente é obrigatória.',
            'ingredients.*.quantity.numeric' => 'A quantidade deve ser um número.',
            'ingredients.*.quantity.min' => 'A quantidade deve ser maior que zero.',
            
            'ingredients.*.unit.required' => 'A unidade do ingrediente é obrigatória.',
            'ingredients.*.unit.max' => 'A unidade não pode ter mais de 50 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descrição',
            'preparation_time' => 'tempo de preparo',
            'servings' => 'porções',
            'preparation_method' => 'instruções',
            'image' => 'imagem',
            'recipe_category_id' => 'categoria',
            'is_active' => 'status ativo',
            'ingredients' => 'ingredientes',
            'ingredients.*.name' => 'nome do ingrediente',
            'ingredients.*.quantity' => 'quantidade',
            'ingredients.*.unit' => 'unidade',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Converter is_active para boolean
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);

        // Limpar ingredientes vazios
        if ($this->has('ingredients')) {
            $ingredients = collect($this->ingredients)
                ->filter(function ($ingredient) {
                    return !empty($ingredient['name']) && 
                           !empty($ingredient['quantity']) && 
                           !empty($ingredient['unit']);
                })
                ->values()
                ->toArray();

            $this->merge(['ingredients' => $ingredients]);
        }
    }
}