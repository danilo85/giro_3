@extends('layouts.app')

@section('title', 'Fontes - Asset Library')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Tags de Navegação Rápida -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5v4"></path>
                </svg>
                Dashboard
            </span>
            <a href="{{ route('assets.images') }}" class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Imagens
            </a>
            <a href="{{ route('assets.fonts') }}" class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707L16.414 6.414A1 1 0 0015.586 6H7a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m-7 0h10"></path>
                </svg>
                Fontes
            </a>
            <a href="{{ route('assets.upload') }}" 
               class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Upload
            </a>
        </div>
    </div>

    <!-- Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('assets.index') }}" 
                       class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Fontes</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie e teste suas fontes</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button id="batch-download-btn" 
                            class="relative inline-flex items-center justify-center w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" 
                            disabled
                            title="Download Selecionadas">
                        <i class="fas fa-download"></i>
                        <span id="selected-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center min-w-[20px] hidden">0</span>
                    </button>
             
                </div>
            </div>
        </div>

    <!-- Font Preview Settings -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Search and Filters -->
                <div class="space-y-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               id="search-input" 
                               placeholder="Buscar fontes..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Format Filter Tags -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button onclick="setFormatFilter('')" 
                                class="format-tag px-3 py-1 text-sm font-medium rounded-full border transition-colors duration-200 bg-blue-600 text-white border-blue-600 active" 
                                data-format="">
                            Todos
                        </button>
                        <button onclick="setFormatFilter('ttf')" 
                                class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" 
                                data-format="ttf">
                            TTF
                        </button>
                        <button onclick="setFormatFilter('otf')" 
                                class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" 
                                data-format="otf">
                            OTF
                        </button>
                        <button onclick="setFormatFilter('woff')" 
                                class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" 
                                data-format="woff">
                            WOFF
                        </button>
                        <button onclick="setFormatFilter('woff2')" 
                                class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" 
                                data-format="woff2">
                            WOFF2
                        </button>
                    </div>

                    <!-- Sort -->
                    <div class="flex space-x-4">
                        <select id="sort-filter" 
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="created_at_desc">Mais recentes</option>
                            <option value="created_at_asc">Mais antigos</option>
                            <option value="name_asc">Nome A-Z</option>
                            <option value="name_desc">Nome Z-A</option>
                            <option value="size_desc">Maior tamanho</option>
                            <option value="size_asc">Menor tamanho</option>
                        </select>
                    </div>
                </div>

                <!-- Preview Settings -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Configurações de Preview</h3>
                    
                    <!-- Preview Text -->
                    <div>
                        <label for="preview-text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Texto de Preview
                        </label>
                        <input type="text" 
                               id="preview-text" 
                               value="The quick brown fox jumps over the lazy dog"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Font Size -->
                    <div class="flex items-center space-x-4">
                        <label for="font-size" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tamanho:
                        </label>
                        <input type="range" 
                               id="font-size" 
                               min="12" 
                               max="72" 
                               value="24" 
                               class="flex-1">
                        <span id="font-size-value" class="text-sm text-gray-600 dark:text-gray-400 w-12">24px</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fonts List -->
        <div id="fonts-container">
            <div id="fonts-list" class="space-y-4">
                @forelse($fonts as $font)
                    <div class="font-item flex flex-col bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-200" 
                         data-id="{{ $font->id }}" 
                         data-name="{{ strtolower($font->original_name) }}" 
                         data-format="{{ strtolower($font->format) }}" 
                         data-size="{{ $font->file_size }}" 
                         data-created="{{ $font->created_at->timestamp }}">
                        
                        <!-- Font Info -->
                        <div class="p-6 flex-grow">
                            <div class="flex items-center space-x-4 mb-4">
                                <!-- Selection Checkbox -->
                                <input type="checkbox" 
                                       class="font-checkbox w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                       value="{{ $font->id }}">
                                
                                <!-- Font Name -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $font->original_name }}
                                    </h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ strtoupper($font->format) }}</span>
                                        <span>{{ $font->formatted_size }}</span>
                                        <span>{{ $font->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Font Preview -->
                            <div class="font-preview-container mb-4">
                                <div class="font-preview p-4 bg-gray-50 dark:bg-gray-700 rounded-lg" 
                                     data-font-url="{{ $font->file_url }}" 
                                     data-font-name="{{ $font->original_name }}">
                                    <div class="font-preview-text text-gray-900 dark:text-white" 
                                         style="font-size: 24px; line-height: 1.4;">
                                        The quick brown fox jumps over the lazy dog
                                    </div>
                                    <div class="font-loading hidden text-center py-8">
                                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                        <span class="ml-2 text-gray-500 dark:text-gray-400">Carregando fonte...</span>
                                    </div>
                                    <div class="font-error hidden text-center py-8">
                                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                                        <span class="ml-2 text-red-500">Erro ao carregar a fonte</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tags -->
                            @if($font->tags->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($font->tags as $tag)
                                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                            {{ $tag->tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Actions Footer -->
                        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 mt-auto">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="toggleFontPreview('{{ $font->id }}')" 
                                        class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 rounded-lg transition-colors duration-200" 
                                        title="Testar Fonte">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('assets.download', $font->id) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600 dark:hover:text-green-400 rounded-lg transition-colors duration-200" 
                                   title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button onclick="deleteFont('{{ $font->id }}')" 
                                        class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition-colors duration-200" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-font text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma fonte encontrada</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Faça upload das suas primeiras fontes</p>
                        <a href="{{ route('assets.upload') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-upload mr-2"></i>
                            Fazer Upload
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($fonts->hasPages())
                <div class="mt-8">
                    {{ $fonts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Floating Action Button (FAB) for Upload -->
<div class="fixed bottom-6 right-6 z-40">
    <a href="{{ route('assets.upload') }}" 
       class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
       title="Upload Fontes">
        <i class="fas fa-upload text-xl"></i>
    </a>
</div>

<!-- Font Test Modal -->
<div id="font-test-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeFontTestModal()"></div>
        
        <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modal-font-name"></h3>
                <button onclick="closeFontTestModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Font Test Controls -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <label for="modal-preview-text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Texto de Teste
                    </label>
                    <textarea id="modal-preview-text" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                              placeholder="Digite o texto que deseja testar com esta fonte...">The quick brown fox jumps over the lazy dog
ABCDEFGHIJKLMNOPQRSTUVWXYZ
abcdefghijklmnopqrstuvwxyz
1234567890 !@#$%^&*()</textarea>
                </div>
                
                <div class="space-y-4">
                    <!-- Font Size -->
                    <div>
                        <label for="modal-font-size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tamanho da Fonte
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="range" 
                                   id="modal-font-size" 
                                   min="8" 
                                   max="120" 
                                   value="24" 
                                   class="flex-1">
                            <span id="modal-font-size-value" class="text-sm text-gray-600 dark:text-gray-400 w-12">24px</span>
                        </div>
                    </div>
                    
                    <!-- Line Height -->
                    <div>
                        <label for="modal-line-height" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Altura da Linha
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="range" 
                                   id="modal-line-height" 
                                   min="0.8" 
                                   max="3" 
                                   step="0.1" 
                                   value="1.4" 
                                   class="flex-1">
                            <span id="modal-line-height-value" class="text-sm text-gray-600 dark:text-gray-400 w-12">1.4</span>
                        </div>
                    </div>
                    
                    <!-- Text Color -->
                    <div>
                        <label for="modal-text-color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cor do Texto
                        </label>
                        <input type="color" 
                               id="modal-text-color" 
                               value="#000000" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                    </div>
                    
                    <!-- Background Color -->
                    <div>
                        <label for="modal-bg-color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cor de Fundo
                        </label>
                        <input type="color" 
                               id="modal-bg-color" 
                               value="#ffffff" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Font Preview Area -->
            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-6 min-h-64" 
                 id="modal-font-preview" 
                 style="background-color: #ffffff;">
                <div id="modal-font-preview-text" 
                     class="font-preview-text" 
                     style="font-size: 24px; line-height: 1.4; color: #000000; white-space: pre-wrap; word-wrap: break-word;">
                    The quick brown fox jumps over the lazy dog
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-between items-center mt-6">
                <div class="text-sm text-gray-500 dark:text-gray-400" id="modal-font-info"></div>
                <div class="flex space-x-2">
                    <a id="modal-download-btn" href="" 
                       title="Download"
                       class="inline-flex items-center justify-center w-10 h-10 sm:px-4 sm:py-2 sm:w-auto sm:h-auto bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-download sm:mr-2"></i>
                        <span class="hidden sm:inline">Download</span>
                    </a>
                    <button id="modal-delete-btn" onclick="deleteFontFromModal()" 
                            title="Excluir"
                            class="inline-flex items-center justify-center w-10 h-10 sm:px-4 sm:py-2 sm:w-auto sm:h-auto bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-trash sm:mr-2"></i>
                        <span class="hidden sm:inline">Excluir</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div id="delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4" style="z-index: 10003;">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="delete-modal-content">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 dark:bg-red-900/20 rounded-full">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">
                Confirmar Exclusão
            </h3>
            
            <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                Tem certeza que deseja excluir a fonte <strong id="delete-font-name"></strong>? Esta ação não pode ser desfeita.
            </p>
            
            <div class="flex space-x-3">
                <button onclick="closeDeleteConfirmModal()" 
                        class="flex-1 px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors duration-200">
                    Cancelar
                </button>
                <button id="confirm-delete-btn" 
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fechar modal ao clicar fora
document.getElementById('delete-confirm-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteConfirmModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('delete-confirm-modal').classList.contains('hidden')) {
            closeDeleteConfirmModal();
        }
    }
});
</script>

@endsection

@push('scripts')
<script src="{{ asset('js/asset-library.js') }}"></script>
<script>
let selectedFonts = new Set();
let currentFontId = null;
let loadedFonts = new Map();
let allFonts = @json($fonts->items());

// Search and Filter
document.getElementById('search-input').addEventListener('input', filterFonts);
document.getElementById('sort-filter').addEventListener('change', filterFonts);

// Format filter state
let currentFormatFilter = '';

// Preview Settings
document.getElementById('preview-text').addEventListener('input', updateAllPreviews);
document.getElementById('font-size').addEventListener('input', function() {
    const size = this.value;
    document.getElementById('font-size-value').textContent = size + 'px';
    updateAllPreviews();
});

// Modal Controls
document.getElementById('modal-preview-text').addEventListener('input', updateModalPreview);
document.getElementById('modal-font-size').addEventListener('input', function() {
    const size = this.value;
    document.getElementById('modal-font-size-value').textContent = size + 'px';
    updateModalPreview();
});
document.getElementById('modal-line-height').addEventListener('input', function() {
    const height = this.value;
    document.getElementById('modal-line-height-value').textContent = height;
    updateModalPreview();
});
document.getElementById('modal-text-color').addEventListener('input', updateModalPreview);
document.getElementById('modal-bg-color').addEventListener('input', updateModalPreview);

// Selection
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('font-checkbox')) {
        const fontId = e.target.value;
        if (e.target.checked) {
            selectedFonts.add(fontId);
        } else {
            selectedFonts.delete(fontId);
        }
        updateSelectionUI();
    }
});

// Batch Download
document.getElementById('batch-download-btn').addEventListener('click', async function() {
    if (selectedFonts.size === 0) {
        alert('Nenhuma fonte selecionada');
        return;
    }

    const fontIds = Array.from(selectedFonts);
    
    // Verificar autenticação antes de fazer a requisição
    try {
        const authResponse = await fetch('/api/auth/check', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        if (authResponse.status === 401) {
            alert('Sessão expirada. Por favor, faça login novamente.');
            window.location.href = '/login';
            return;
        }
        
        if (!authResponse.ok) {
            throw new Error('Erro na verificação de autenticação');
        }
    } catch (authError) {
        console.error('Erro ao verificar autenticação:', authError);
        alert('Erro de autenticação. Por favor, faça login novamente.');
        window.location.href = '/login';
        return;
    }
    
    try {
        const response = await fetch('/assets/download-batch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                asset_ids: fontIds
            })
        });

        if (response.ok) {
            // Verificar se a resposta é realmente um arquivo ZIP
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/zip')) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'fontes.zip';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                // Resposta não é um ZIP, provavelmente HTML de erro
                const text = await response.text();
                console.error('Resposta inesperada:', text);
                alert('Erro: Você precisa estar logado para fazer download. Por favor, faça login novamente.');
                window.location.href = '/login';
            }
        } else {
            // Tentar ler como JSON primeiro, se falhar, ler como texto
            try {
                const errorData = await response.json();
                alert('Erro no download: ' + (errorData.error || 'Erro desconhecido'));
            } catch (jsonError) {
                const text = await response.text();
                console.error('Erro de resposta:', text);
                if (text.includes('<!DOCTYPE') || text.includes('<html>')) {
                    alert('Erro: Sessão expirada. Por favor, faça login novamente.');
                    window.location.href = '/login';
                } else {
                    alert('Erro no download: Resposta inválida do servidor');
                }
            }
        }
    } catch (error) {
        console.error('Erro no download:', error);
        alert('Erro no download: ' + error.message);
    }
});

function setFormatFilter(format) {
    currentFormatFilter = format;
    
    // Update active state of format tags
    document.querySelectorAll('.format-tag').forEach(tag => {
        if (tag.dataset.format === format) {
            tag.classList.add('active');
            tag.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50', 'dark:border-gray-600', 'dark:text-gray-300', 'dark:hover:bg-gray-700');
            tag.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
        } else {
            tag.classList.remove('active', 'bg-blue-600', 'text-white', 'border-blue-600');
            tag.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50', 'dark:border-gray-600', 'dark:text-gray-300', 'dark:hover:bg-gray-700');
        }
    });
    
    filterFonts();
}

function filterFonts() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const formatFilter = currentFormatFilter;
    const sortFilter = document.getElementById('sort-filter').value;
    
    const fontItems = document.querySelectorAll('.font-item');
    let visibleItems = [];
    
    fontItems.forEach(item => {
        const name = item.dataset.name;
        const format = item.dataset.format;
        
        const matchesSearch = !searchTerm || name.includes(searchTerm);
        const matchesFormat = !formatFilter || format === formatFilter;
        
        if (matchesSearch && matchesFormat) {
            item.style.display = 'block';
            visibleItems.push(item);
        } else {
            item.style.display = 'none';
        }
    });
    
    // Sort visible items
    if (visibleItems.length > 0) {
        sortFonts(visibleItems, sortFilter);
    }
}

function sortFonts(items, sortBy) {
    const container = document.getElementById('fonts-list');
    
    items.sort((a, b) => {
        switch (sortBy) {
            case 'created_at_desc':
                return parseInt(b.dataset.created) - parseInt(a.dataset.created);
            case 'created_at_asc':
                return parseInt(a.dataset.created) - parseInt(b.dataset.created);
            case 'name_asc':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'name_desc':
                return b.dataset.name.localeCompare(a.dataset.name);
            case 'size_desc':
                return parseInt(b.dataset.size) - parseInt(a.dataset.size);
            case 'size_asc':
                return parseInt(a.dataset.size) - parseInt(b.dataset.size);
            default:
                return 0;
        }
    });
    
    items.forEach(item => container.appendChild(item));
}

function updateSelectionUI() {
    const count = selectedFonts.size;
    const countElement = document.getElementById('selected-count');
    const downloadBtn = document.getElementById('batch-download-btn');
    
    countElement.textContent = count;
    downloadBtn.disabled = count === 0;
    
    if (count > 0) {
        countElement.classList.remove('hidden');
    } else {
        countElement.classList.add('hidden');
    }
}

function updateAllPreviews() {
    const previewText = document.getElementById('preview-text').value;
    const fontSize = document.getElementById('font-size').value + 'px';
    
    document.querySelectorAll('.font-preview-text').forEach(element => {
        element.textContent = previewText;
        element.style.fontSize = fontSize;
    });
}

function loadFont(fontUrl, fontName) {
    if (loadedFonts.has(fontUrl)) {
        return Promise.resolve(loadedFonts.get(fontUrl));
    }
    
    return new Promise((resolve, reject) => {
        const font = new FontFace(fontName, `url(${fontUrl})`);
        
        font.load().then(loadedFont => {
            document.fonts.add(loadedFont);
            loadedFonts.set(fontUrl, fontName);
            resolve(fontName);
        }).catch(error => {
            console.error('Error loading font:', error);
            reject(error);
        });
    });
}

function toggleFontPreview(fontId) {
    const font = allFonts.find(f => f.id === fontId);
    if (!font) return;
    
    currentFontId = fontId;
    
    document.getElementById('modal-font-name').textContent = font.original_name;
    document.getElementById('modal-font-info').textContent = `${font.format.toUpperCase()} • ${font.formatted_size}`;
    document.getElementById('modal-download-btn').href = `/assets/${fontId}/download`;
    
    // Load and apply font
    const previewElement = document.getElementById('modal-font-preview-text');
    previewElement.style.fontFamily = 'inherit'; // Reset to default
    
    loadFont(font.file_url, font.original_name)
        .then(fontName => {
            previewElement.style.fontFamily = `"${fontName}", sans-serif`;
        })
        .catch(error => {
            console.error('Failed to load font:', error);
            previewElement.style.fontFamily = 'monospace'; // Fallback
        });
    
    document.getElementById('font-test-modal').classList.remove('hidden');
}

function closeFontTestModal() {
    document.getElementById('font-test-modal').classList.add('hidden');
    currentFontId = null;
}

function updateModalPreview() {
    const previewText = document.getElementById('modal-preview-text').value;
    const fontSize = document.getElementById('modal-font-size').value + 'px';
    const lineHeight = document.getElementById('modal-line-height').value;
    const textColor = document.getElementById('modal-text-color').value;
    const bgColor = document.getElementById('modal-bg-color').value;
    
    const previewElement = document.getElementById('modal-font-preview-text');
    const previewContainer = document.getElementById('modal-font-preview');
    
    previewElement.textContent = previewText;
    previewElement.style.fontSize = fontSize;
    previewElement.style.lineHeight = lineHeight;
    previewElement.style.color = textColor;
    previewContainer.style.backgroundColor = bgColor;
}

let fontToDelete = null;

function deleteFont(fontId) {
    const font = allFonts.find(f => f.id === fontId);
    if (font) {
        openDeleteConfirmModal(fontId, font.original_name);
    }
}

function openDeleteConfirmModal(fontId, fontName) {
    fontToDelete = fontId;
    document.getElementById('delete-font-name').textContent = fontName;
    
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = document.getElementById('delete-modal-content');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Set up confirm button
    const confirmBtn = document.getElementById('confirm-delete-btn');
    confirmBtn.onclick = () => confirmDelete();
}

function closeDeleteConfirmModal() {
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = document.getElementById('delete-modal-content');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        fontToDelete = null;
    }, 300);
}

function confirmDelete() {
    if (!fontToDelete) return;
    
    const confirmBtn = document.getElementById('confirm-delete-btn');
    const originalText = confirmBtn.innerHTML;
    
    // Show loading state
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Excluindo...';
    confirmBtn.disabled = true;
    
    fetch(`/assets/${fontToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-id="${fontToDelete}"]`).remove();
            selectedFonts.delete(fontToDelete);
            updateSelectionUI();
            closeDeleteConfirmModal();
            showSuccessMessage('Fonte excluída com sucesso!');
        } else {
            showErrorMessage('Erro ao excluir a fonte');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Erro ao excluir a fonte');
    })
    .finally(() => {
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

function showSuccessMessage(message) {
    createToast(message, 'success');
}

function showErrorMessage(message) {
    createToast(message, 'error');
}

function createToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-medium z-50 transform transition-all duration-300 translate-x-full opacity-0 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

function deleteFontFromModal() {
    if (currentFontId) {
        const font = allFonts.find(f => f.id === currentFontId);
        if (font) {
            closeFontTestModal();
            openDeleteConfirmModal(currentFontId, font.original_name);
        }
    }
}

// Initialize font previews on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.font-preview').forEach(preview => {
        const fontUrl = preview.dataset.fontUrl;
        const fontName = preview.dataset.fontName;
        const textElement = preview.querySelector('.font-preview-text');
        const loadingElement = preview.querySelector('.font-loading');
        const errorElement = preview.querySelector('.font-error');
        
        if (fontUrl && fontName) {
            loadingElement.classList.remove('hidden');
            textElement.classList.add('hidden');
            
            loadFont(fontUrl, fontName)
                .then(loadedFontName => {
                    textElement.style.fontFamily = `"${loadedFontName}", sans-serif`;
                    loadingElement.classList.add('hidden');
                    textElement.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Failed to load font:', error);
                    loadingElement.classList.add('hidden');
                    errorElement.classList.remove('hidden');
                });
        }
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFontTestModal();
    }
});
</script>
@endpush