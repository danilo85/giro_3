@extends('layouts.app')

@section('title', 'Receitas')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="recipesPage()">
    <!-- Header com Tags de Navegação -->
    <div class="mb-6">
        <!-- Tags de Navegação Rápida -->
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-home mr-2"></i>
                Dashboard
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <i class="fas fa-utensils mr-2"></i>
                Receitas
            </span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Receitas</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie suas receitas culinárias favoritas</p>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Receitas -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Receitas</p>
                    <p class="text-2xl font-bold">{{ $recipes->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Receitas Ativas -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Receitas Ativas</p>
                    <p class="text-2xl font-bold">{{ $recipes->where('is_active', true)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Receitas com Imagem -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Com Imagem</p>
                    <p class="text-2xl font-bold">{{ $recipes->whereNotNull('image_path')->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Categorias -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Categorias</p>
                    <p class="text-2xl font-bold">{{ $categories->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros e Busca -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Campo de Busca -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="block w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                       placeholder="Buscar por nome, descrição ou ingredientes..." 
                       value="{{ request('search') }}">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" 
                            id="clear-search" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" 
                            style="display: none;">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filtro de Categoria -->
            <div class="w-full md:w-48">
                <select id="category-filter" class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Todas as Categorias</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro de Status -->
            <div class="w-full md:w-48">
                <select id="status-filter" class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Todos os Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativas</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Grid de Cards de Receitas -->
    @if($recipes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="recipes-grid">
            @foreach($recipes as $recipe)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 group flex flex-col h-full" 
                     data-recipe-id="{{ $recipe->id }}">
                    <!-- Imagem da Receita -->
                    <div class="relative h-48 rounded-t-lg overflow-hidden">
                        @if($recipe->image_path)
                            <img src="{{ $recipe->image_url }}" 
                                 alt="{{ $recipe->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        <!-- Badge de Status -->
                        <div class="absolute top-3 left-3">
                            @if($recipe->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Ativa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inativa
                                </span>
                            @endif
                        </div>
                        <!-- Badge de Categoria -->
                        @if($recipe->category)
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white" 
                                      style="background-color: {{ $recipe->category->color }}">
                                    {{ $recipe->category->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Conteúdo do Card -->
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Título e Descrição -->
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $recipe->name }}</h3>
                            @if($recipe->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ $recipe->description }}</p>
                            @endif
                        </div>
                        
                        <!-- Informações da Receita -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $recipe->formatted_preparation_time }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Preparo</div>
                            </div>
                            <div class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $recipe->servings }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Porções</div>
                            </div>
                        </div>
                        
                        <!-- Ingredientes -->
                        @if($recipe->ingredients->count() > 0)
                            <div class="mb-4">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Ingredientes principais:</div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($recipe->ingredients->take(3) as $ingredient)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $ingredient->name }}
                                        </span>
                                    @endforeach
                                    @if($recipe->ingredients->count() > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            +{{ $recipe->ingredients->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Data de Criação -->
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                            Criada em {{ $recipe->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    
                    <!-- Footer do Card com Ações -->
                    <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <div class="flex space-x-3">
                            <a href="{{ route('recipes.show', $recipe) }}" 
                               class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                               title="Visualizar Receita">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('recipes.edit', $recipe) }}" 
                               class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-900/20" 
                               title="Editar Receita">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <!-- Toggle Status -->
                            <button type="button" 
                                    onclick="toggleRecipeStatus({{ $recipe->id }})" 
                                    class="p-2 rounded-lg transition-all duration-200 {{ $recipe->is_active ? 'text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20' : 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20' }}" 
                                    title="{{ $recipe->is_active ? 'Desativar Receita' : 'Ativar Receita' }}">
                                @if($recipe->is_active)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </button>
                            <button onclick="deleteRecipe({{ $recipe->id }}, '{{ addslashes($recipe->name) }}')" 
                                    class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                    title="Excluir Receita">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado Vazio -->
        <div class="text-center py-12">
            <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma receita encontrada</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Comece criando sua primeira receita culinária.</p>
            <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Criar Primeira Receita
            </a>
        </div>
    @endif

    <!-- Paginação -->
    @if($recipes->hasPages())
        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <!-- Informações da Paginação -->
            <div class="pagination-info text-sm text-gray-600 dark:text-gray-400">
                Mostrando {{ $recipes->firstItem() }} a {{ $recipes->lastItem() }} de {{ $recipes->total() }} resultados
            </div>
            
            <!-- Links de Paginação -->
            <div class="pagination-wrapper">
                {{ $recipes->links('pagination.custom') }}
            </div>
        </div>
    @endif

</div> <!-- Botão Flutuante de Criação -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="{{ route('recipes.create') }}" 
           class="group fixed bottom-6 right-6 z-50 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center justify-center w-14 h-14"
           title="Adicionar Nova Receita">
            <svg class="w-6 h-6 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </a>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Confirmar Exclusão</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja excluir a receita <span id="recipe-name" class="font-semibold"></span>? Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirm-delete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Excluir
                </button>
                <button id="cancel-delete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
<footer class="mt-8">
    <div class="text-center py-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            © {{ date('Y') }} Danilo Miguel. Todos os direitos reservados.
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
            Sistema de Gestão Financeira - Desenvolvido com Laravel
        </p>
    </div>
</footer>
@endsection

@push('scripts')
<script>
function recipesPage() {
    return {
        init() {
            this.initSearch();
            this.initFilters();
        },

        initSearch() {
            const searchInput = document.getElementById('search');
            const clearButton = document.getElementById('clear-search');
            
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    if (e.target.value) {
                        clearButton.style.display = 'block';
                    } else {
                        clearButton.style.display = 'none';
                    }
                    this.filterRecipes();
                });

                if (searchInput.value) {
                    clearButton.style.display = 'block';
                }
            }

            if (clearButton) {
                clearButton.addEventListener('click', () => {
                    searchInput.value = '';
                    clearButton.style.display = 'none';
                    this.filterRecipes();
                });
            }
        },

        initFilters() {
            const categoryFilter = document.getElementById('category-filter');
            const statusFilter = document.getElementById('status-filter');
            
            if (categoryFilter) {
                categoryFilter.addEventListener('change', () => {
                    this.applyFilters();
                });
            }
            
            if (statusFilter) {
                statusFilter.addEventListener('change', () => {
                    this.applyFilters();
                });
            }
        },

        applyFilters() {
            // Construir URL com parâmetros de filtro
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams();
            
            const searchTerm = document.getElementById('search').value;
            const categoryFilter = document.getElementById('category-filter').value;
            const statusFilter = document.getElementById('status-filter').value;
            
            if (searchTerm) {
                searchParams.set('search', searchTerm);
            }
            
            if (categoryFilter) {
                searchParams.set('category', categoryFilter);
            }
            
            // Sempre enviar o parâmetro status, mesmo se for vazio
            searchParams.set('status', statusFilter);
            
            // Redirecionar com os filtros aplicados
            url.search = searchParams.toString();
            window.location.href = url.toString();
        },

        filterRecipes() {
            // Manter a funcionalidade de busca em tempo real apenas para o campo de busca
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const recipeCards = document.querySelectorAll('[data-recipe-id]');

            recipeCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p')?.textContent.toLowerCase() || '';
                
                let showCard = true;

                // Filtro de busca em tempo real
                if (searchTerm && !title.includes(searchTerm) && !description.includes(searchTerm)) {
                    showCard = false;
                }

                card.style.display = showCard ? 'block' : 'none';
            });
        }
    }
}

function toggleRecipeStatus(recipeId) {
    fetch(`/utilidades/receitas/${recipeId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao alterar status da receita');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao alterar status da receita');
    });
}

function deleteRecipe(recipeId, recipeTitle) {
    const modal = document.getElementById('delete-modal');
    const recipeNameSpan = document.getElementById('recipe-name');
    const confirmButton = document.getElementById('confirm-delete');
    const cancelButton = document.getElementById('cancel-delete');
    
    recipeNameSpan.textContent = recipeTitle;
    modal.classList.remove('hidden');
    
    confirmButton.onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/utilidades/receitas/${recipeId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    };
    
    cancelButton.onclick = function() {
        modal.classList.add('hidden');
    };
    
    // Fechar modal clicando fora
    modal.onclick = function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    };
}
</script>

<style>
/* Estilos de Paginação */
.pagination {
    display: flex !important;
    justify-content: center !important;
    align-items: center;
    gap: 0.5rem;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
    background: transparent !important;
    border: none !important;
}

.page-item {
    margin: 0 !important;
}

.page-link {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 44px !important;
    height: 44px !important;
    padding: 0.5rem 0.75rem !important;
    color: #374151 !important;
    background-color: #ffffff !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    font-weight: 500 !important;
    font-size: 0.875rem !important;
    transition: all 0.3s ease !important;
}

.page-link:hover {
    color: #ffffff !important;
    background-color: #3b82f6 !important;
    border-color: #3b82f6 !important;
    transform: translateY(-1px) !important;
}

.page-item.active .page-link {
    color: #ffffff !important;
    background-color: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
}

.page-item.disabled .page-link {
    color: #9ca3af !important;
    background-color: #f9fafb !important;
    border-color: #e5e7eb !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.page-item.disabled .page-link:hover {
    color: #9ca3af !important;
    background-color: #f9fafb !important;
    border-color: #e5e7eb !important;
    transform: none !important;
}

/* Pagination arrows */
.page-link[rel="prev"],
.page-link[rel="next"] {
    font-weight: 600;
    padding: 0.5rem 1rem;
}

.page-link[rel="prev"]:before {
    content: "‹";
    margin-right: 0.25rem;
}

.page-link[rel="next"]:after {
    content: "›";
    margin-left: 0.25rem;
}

/* Pagination info text */
.pagination-info {
    text-align: center;
    margin-bottom: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination {
        gap: 0.25rem;
    }
    
    .page-link {
        min-width: 40px !important;
        height: 40px !important;
        padding: 0.375rem 0.5rem !important;
        font-size: 0.8rem !important;
    }
    
    .pagination-info {
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .page-link {
        color: #e5e7eb !important;
        background-color: #374151 !important;
        border-color: #4b5563 !important;
    }
    
    .page-link:hover {
        color: #ffffff !important;
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
    }
    
    .page-item.active .page-link {
        color: #ffffff !important;
        background-color: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
    }
    
    .page-item.disabled .page-link {
        color: #6b7280 !important;
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }
    
    .page-item.disabled .page-link:hover {
        color: #6b7280 !important;
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }
    
    .pagination-info {
        color: #9ca3af;
    }
}
</style>

@endpush