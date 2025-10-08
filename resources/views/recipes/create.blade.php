@extends('layouts.app')

@section('title', 'Nova Receita')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="createRecipePage()">
    <!-- Header com Tags de Navegação -->
    <div class="mb-6">
        <!-- Tags de Navegação Rápida -->
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-home mr-2"></i>
                Dashboard
            </a>
            <a href="{{ route('recipes.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-utensils mr-2"></i>
                Receitas
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <i class="fas fa-plus mr-2"></i>
                Nova Receita
            </span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Nova Receita</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Crie uma nova receita culinária</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('recipes.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                   title="Voltar para lista de receitas">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Mensagens de Erro e Sucesso -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Há erros no formulário:
                    </h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulário -->
    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Coluna Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informações Básicas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações Básicas</h2>
                    
                    <div class="space-y-4">
                        <!-- Título -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Título da Receita <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-500 @enderror" 
                                   placeholder="Ex: Bolo de Chocolate Fofinho"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descrição
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror" 
                                      placeholder="Descreva brevemente sua receita...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempo de Preparo e Porções -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="preparation_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tempo de Preparo (minutos) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="preparation_time" 
                                       name="preparation_time" 
                                       value="{{ old('preparation_time') }}"
                                       min="1"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('preparation_time') border-red-500 @enderror" 
                                       placeholder="30"
                                       required>
                                @error('preparation_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="servings" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Porções <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="servings" 
                                       name="servings" 
                                       value="{{ old('servings') }}"
                                       min="1"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('servings') border-red-500 @enderror" 
                                       placeholder="4"
                                       required>
                                @error('servings')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ingredientes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ingredientes</h2>
                        <button type="button" 
                                @click="addIngredient()"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar Ingrediente
                        </button>
                    </div>
                    
                    <div id="ingredients-container" class="space-y-3 relative">
                        <!-- Ingredientes serão adicionados aqui via JavaScript -->
                    </div>
                    
                    @error('ingredients')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modo de Preparo -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Modo de Preparo</h2>
                    
                    <div>
                        <label for="preparation_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Instruções de Preparo <span class="text-red-500">*</span>
                        </label>
                        <textarea id="preparation_method"
                                  name="preparation_method"
                                  rows="6"
                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('preparation_method') border-red-500 @enderror"
                                  placeholder="Descreva o passo a passo para preparar a receita..."
                                  required>{{ old('preparation_method') }}</textarea>
                        @error('preparation_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Coluna Lateral -->
            <div class="space-y-6">
                <!-- Upload de Imagem -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Imagem da Receita</h2>
                    
                    <div class="space-y-4">
                        <!-- Preview da Imagem -->
                        <div id="image-preview" class="hidden">
                            <img id="preview-img" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                            <button type="button" 
                                    @click="removeImage()"
                                    class="mt-2 w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Remover Imagem
                            </button>
                        </div>
                        
                        <!-- Upload Area -->
                        <div id="upload-area" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer">
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="hidden"
                                   @change="previewImage($event)">
                            <label for="image" class="cursor-pointer">
                                <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-blue-600 dark:text-blue-400">Clique para fazer upload</span>
                                    ou arraste uma imagem
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG até 2MB</p>
                            </label>
                        </div>
                        
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Categoria -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Categoria</h2>
                    
                    <div>
                        <label for="recipe_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Categoria <span class="text-red-500">*</span>
                        </label>
                        <select id="recipe_category_id"
                                name="recipe_category_id"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('recipe_category_id') border-red-500 @enderror"
                                required>
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('recipe_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('recipe_category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Status</h2>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Receita ativa
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Receitas ativas são exibidas na listagem
                    </p>
                </div>

                <!-- Botões de Ação -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Receita
                        </button>
                        
                        <a href="{{ route('recipes.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function createRecipePage() {
    return {
        ingredientCount: 0,
        
        init() {
            this.addIngredient(); // Adicionar um ingrediente inicial
        },

        addIngredient() {
            const container = document.getElementById('ingredients-container');
            const ingredientDiv = document.createElement('div');
            ingredientDiv.className = 'flex flex-col sm:flex-row gap-3 items-start ingredient-row';
            ingredientDiv.innerHTML = `
                <div class="flex-1 relative">
                    <input type="text" 
                           name="ingredients[${this.ingredientCount}][name]" 
                           placeholder="Nome do ingrediente"
                           class="ingredient-search block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           required>
                    <div class="ingredient-suggestions absolute z-50 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg hidden max-h-40 overflow-y-auto mt-1"></div>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <div class="flex-1 sm:w-20">
                        <input type="number" 
                               name="ingredients[${this.ingredientCount}][quantity]" 
                               placeholder="Qtd"
                               step="0.01"
                               min="0"
                               class="quantity-input block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               required>
                    </div>
                    <div class="flex-1 sm:w-32">
                        <select name="ingredients[${this.ingredientCount}][unit]" 
                                class="unit-select block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                            <option value="unidade" selected>unidade</option>
                        </select>
                    </div>
                </div>
                <button type="button" 
                        onclick="this.closest('.ingredient-row').remove()"
                        class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            
            container.appendChild(ingredientDiv);
            this.ingredientCount++;
            
            // Adicionar funcionalidade de autocomplete e conversão
            this.initIngredientAutocomplete(ingredientDiv.querySelector('.ingredient-search'));
            this.initUnitConverter(ingredientDiv);
        },

        initIngredientAutocomplete(input) {
            const suggestionsDiv = input.nextElementSibling;
            const ingredientRow = input.closest('.ingredient-row');
            const unitSelect = ingredientRow.querySelector('.unit-select');
            const self = this;
            
            input.addEventListener('input', async function() {
                const query = this.value.trim();
                
                if (query.length < 2) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }
                
                try {
                    const response = await fetch(`/utilidades/receitas/ingredients/search?q=${encodeURIComponent(query)}`);
                    const ingredients = await response.json();
                    
                    if (ingredients.length > 0) {
                        suggestionsDiv.innerHTML = ingredients.map(ingredient => 
                            `<div class="p-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                                 data-ingredient-name="${ingredient.name}" 
                                 data-ingredient-unit="${ingredient.default_unit}">
                                ${ingredient.name} <span class="text-sm text-gray-500">(${ingredient.default_unit})</span>
                            </div>`
                        ).join('');
                        suggestionsDiv.classList.remove('hidden');
                        
                        // Adicionar event listeners para as sugestões
                        suggestionsDiv.querySelectorAll('[data-ingredient-name]').forEach(item => {
                            item.addEventListener('click', () => {
                                const name = item.dataset.ingredientName;
                                const defaultUnit = item.dataset.ingredientUnit;
                                self.selectIngredient(name, defaultUnit, ingredientRow);
                            });
                        });
                    } else {
                        suggestionsDiv.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Erro ao buscar ingredientes:', error);
                    suggestionsDiv.classList.add('hidden');
                }
            });
            
            // Fechar sugestões ao clicar fora
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                    suggestionsDiv.classList.add('hidden');
                }
            });
        },

        selectIngredient(name, defaultUnit, ingredientRow) {
            const input = ingredientRow.querySelector('.ingredient-search');
            const unitSelect = ingredientRow.querySelector('.unit-select');
            const suggestionsDiv = ingredientRow.querySelector('.ingredient-suggestions');
            
            // Preencher o nome do ingrediente
            input.value = name;
            
            // Atualizar o select de unidade com a unidade padrão
            this.updateUnitSelect(unitSelect, defaultUnit);
            
            // Esconder sugestões
            suggestionsDiv.classList.add('hidden');
        },

        updateUnitSelect(selectElement, defaultUnit) {
            // Lista de unidades disponíveis
            const units = [
                'unidade', 'kg', 'g', 'l', 'ml', 'xícara', 'colher de sopa', 
                'colher de chá', 'pitada', 'dente', 'fatia', 'pedaço', 'lata', 
                'pacote', 'maço', 'ramo', 'folha', 'a gosto'
            ];
            
            // Limpar opções existentes
            selectElement.innerHTML = '';
            
            // Adicionar a unidade padrão primeiro (selecionada)
            const defaultOption = document.createElement('option');
            defaultOption.value = defaultUnit;
            defaultOption.textContent = defaultUnit;
            defaultOption.selected = true;
            selectElement.appendChild(defaultOption);
            
            // Adicionar outras unidades (exceto a padrão para evitar duplicação)
            units.filter(unit => unit !== defaultUnit).forEach(unit => {
                const option = document.createElement('option');
                option.value = unit;
                option.textContent = unit;
                selectElement.appendChild(option);
            });
        },

        initUnitConverter(ingredientDiv) {
            // Configurar o campo de quantidade para aceitar decimais corretamente
            const quantityInput = ingredientDiv.querySelector('.quantity-input');
            
            if (quantityInput) {
                // Garantir que aceite decimais com ponto
                quantityInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    
                    // Permitir apenas números e um ponto decimal
                    value = value.replace(/[^0-9.]/g, '');
                    
                    // Garantir apenas um ponto decimal
                    const parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts[0] + '.' + parts.slice(1).join('');
                    }
                    
                    e.target.value = value;
                });
                
                // Validar ao sair do campo
                quantityInput.addEventListener('blur', function(e) {
                    let value = parseFloat(e.target.value);
                    if (isNaN(value) || value <= 0) {
                        e.target.value = '';
                    } else {
                        // Formatar com até 2 casas decimais
                        e.target.value = value.toString();
                    }
                });
            }
        },

        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-area').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        },

        removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('upload-area').classList.remove('hidden');
        }
    }
}
</script>
@endpush