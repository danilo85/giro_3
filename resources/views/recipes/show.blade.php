@extends('layouts.app')

@section('title', $recipe->name)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
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
                <i class="fas fa-eye mr-2"></i>
                {{ $recipe->name }}
            </span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $recipe->name }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    @if($recipe->category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" 
                              style="background-color: {{ $recipe->category->color }}">
                            {{ $recipe->category->name }}
                        </span>
                    @endif
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
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('recipes.edit', $recipe) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                   title="Editar receita">
                    <i class="fas fa-edit text-lg"></i>
                </a>
                <a href="{{ route('recipes.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                   title="Voltar para lista de receitas">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Imagem da Receita -->
            @if($recipe->image_path)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <img src="{{ $recipe->image_url }}" 
                         alt="{{ $recipe->name }}" 
                         class="w-full h-64 md:h-80 object-cover">
                </div>
            @endif

            <!-- Descrição -->
            @if($recipe->description)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Descrição</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $recipe->description }}</p>
                </div>
            @endif

            <!-- Ingredientes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Ingredientes</h2>
                
                @if($recipe->recipeIngredients->count() > 0)
                    <div class="space-y-3">
                        @foreach($recipe->recipeIngredients as $recipeIngredient)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <span class="text-gray-900 dark:text-white font-medium">
                                        {{ $recipeIngredient->ingredient->name }}
                                    </span>
                                </div>
                                <span class="text-gray-600 dark:text-gray-400 font-medium">
                                    {{ $recipeIngredient->formatted_quantity }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Nenhum ingrediente cadastrado</p>
                    </div>
                @endif
            </div>

            <!-- Modo de Preparo -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Modo de Preparo</h2>
                
                @if($recipe->preparation_method)
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <div class="text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-line">{{ $recipe->preparation_method }}</div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Nenhuma instrução cadastrada</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Coluna Lateral -->
        <div class="space-y-6">
            <!-- Informações Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações</h2>
                
                <div class="space-y-4">
                    <!-- Tempo de Preparo -->
                    <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Tempo de Preparo</div>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $recipe->formatted_preparation_time }}</div>
                            </div>
                        </div>
                        <div class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                            {{ $recipe->preparation_time_category }}
                        </div>
                    </div>

                    <!-- Porções -->
                    <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Porções</div>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $recipe->servings }} {{ $recipe->servings == 1 ? 'pessoa' : 'pessoas' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Ingredientes -->
                    <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Ingredientes</div>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $recipe->recipeIngredients->count() }} {{ $recipe->recipeIngredients->count() == 1 ? 'item' : 'itens' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Ações</h2>
                
                <div class="space-y-3">
                    <a href="{{ route('recipes.edit', $recipe) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Receita
                    </a>
                    
                    <button onclick="toggleRecipeStatus({{ $recipe->id }})" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 {{ $recipe->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors">
                        @if($recipe->is_active)
                            <i class="fas fa-eye-slash mr-2"></i>
                            Desativar Receita
                        @else
                            <i class="fas fa-eye mr-2"></i>
                            Ativar Receita
                        @endif
                    </button>
                    
                    <button onclick="deleteRecipe({{ $recipe->id }}, '{{ addslashes($recipe->name) }}')" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Excluir Receita
                    </button>
                </div>
            </div>

            <!-- Metadados -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações Adicionais</h2>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Criada em:</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $recipe->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    @if($recipe->updated_at != $recipe->created_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Atualizada em:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $recipe->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Autor:</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $recipe->user->name }}</span>
                    </div>
                </div>
            </div>
        </div>
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

@endsection

@push('scripts')
<script>
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
@endpush