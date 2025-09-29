@extends('layouts.app')

@section('title', 'Imagens - Asset Library')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Tags de Navegação Rápida -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('assets.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5v4"></path>
                </svg>
                Dashboard
            </a>
            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                </svg>
                Imagens
            </span>
            <a href="{{ route('assets.fonts') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707L16.414 6.414A1 1 0 0015.586 6H7a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m-7 0h10"></path>
                </svg>
                Fontes
            </a>
            <a href="{{ route('assets.upload') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Imagens</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie suas imagens</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button id="batch-download-btn" 
                            class="relative inline-flex items-center justify-center w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" 
                            disabled
                            title="Download Selecionados">
                        <i class="fas fa-download"></i>
                        <span id="selected-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center min-w-[20px] hidden">0</span>
                    </button>
       
                </div>
            </div>
        </div>

    <!-- Filters and Search -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <!-- Search Input -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" 
                           id="search-input" 
                           placeholder="Buscar imagens..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Tags Rápidas de Formatos -->
            <div class="mb-4">
                <div class="flex flex-wrap gap-2">
                    <button class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" data-format="">
                        Todos
                    </button>
                    <button class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" data-format="jpg">
                        JPG
                    </button>
                    <button class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" data-format="png">
                        PNG
                    </button>
                    <button class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" data-format="gif">
                        GIF
                    </button>
                    <button class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" data-format="webp">
                        WebP
                    </button>
                    <button class="format-tag px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200" data-format="svg">
                        SVG
                    </button>
                </div>
            </div>

            <!-- Filtros e Visualização -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <!-- Filtros -->
                <div class="flex items-center space-x-4">
                    <!-- Sort -->
                    <select id="sort-filter" 
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="created_at_desc">Mais recentes</option>
                        <option value="created_at_asc">Mais antigos</option>
                        <option value="name_asc">Nome A-Z</option>
                        <option value="name_desc">Nome Z-A</option>
                        <option value="size_desc">Maior tamanho</option>
                        <option value="size_asc">Menor tamanho</option>
                    </select>
                </div>

                <!-- View Mode -->
                <div class="flex">
                    <button id="grid-view" 
                            class="p-2 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 rounded-l-lg transition-colors duration-200">
                        <i class="fas fa-th"></i>
                    </button>
                    <button id="list-view" 
                            class="p-2 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 rounded-r-lg transition-colors duration-200">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Images Grid -->
        <div id="images-container">
            <div id="images-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($images as $image)
                    <div class="image-item group relative flex flex-col bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-200" 
                         data-id="{{ $image->id }}" 
                         data-name="{{ strtolower($image->original_name) }}" 
                         data-format="{{ strtolower($image->format) }}" 
                         data-size="{{ $image->file_size }}" 
                         data-created="{{ $image->created_at->timestamp }}">
                        
                        <!-- Selection Checkbox -->
                        <div class="absolute top-2 left-2 z-10">
                            <input type="checkbox" 
                                   class="image-checkbox w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                   value="{{ $image->id }}">
                        </div>

                        <!-- Image Preview -->
                        <div class="aspect-square rounded-t-lg overflow-hidden bg-gray-100 dark:bg-gray-700 cursor-pointer" 
                             onclick="openImageModal('{{ $image->id }}')">
                            <img src="{{ $image->thumbnail_url }}" 
                                 alt="{{ $image->original_name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                 loading="lazy">
                        </div>

                        <!-- Image Info -->
                        <div class="p-3 flex-grow">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate" 
                                title="{{ $image->original_name }}">
                                {{ $image->original_name }}
                            </h3>
                            <div class="flex items-center justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $image->formatted_size }}</span>
                                <span>{{ strtoupper($image->format) }}</span>
                            </div>
                            @if($image->width && $image->height)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $image->width }} × {{ $image->height }}
                                </div>
                            @endif
                            @if($image->tags->count() > 0)
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($image->tags->take(2) as $tag)
                                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                            {{ $tag->tag }}
                                        </span>
                                    @endforeach
                                    @if($image->tags->count() > 2)
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                                            +{{ $image->tags->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Actions Footer -->
                        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 mt-auto">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="openImageModal('{{ $image->id }}')" 
                                        class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors duration-200" 
                                        title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('assets.download', $image->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600 dark:hover:text-green-400 rounded-lg transition-colors duration-200" 
                                   title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button onclick="deleteImage('{{ $image->id }}')" 
                                        class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition-colors duration-200" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 dark:text-gray-500 mb-4">
                            <i class="fas fa-images text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma imagem encontrada</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Comece fazendo upload de suas primeiras imagens.</p>
                        <a href="{{ route('assets.upload') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-upload mr-2"></i>
                            Fazer Upload
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($images->hasPages())
                <div class="mt-6">
                    {{ $images->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modal-title">Visualizar Imagem</h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <div id="modal-content">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button (FAB) for Upload -->
<div class="fixed bottom-6 right-6 z-40">
    <a href="{{ route('assets.upload') }}" 
       class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
       title="Upload Imagens">
        <i class="fas fa-upload text-xl"></i>
    </a>
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
                Tem certeza que deseja excluir a imagem <strong id="delete-image-name"></strong>? Esta ação não pode ser desfeita.
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
<script>
// Variável para armazenar imagens selecionadas
const selectedImages = new Set();

// Search functionality
document.getElementById('search-input').addEventListener('input', function() {
    filterImages();
});

// Format tags functionality
document.querySelectorAll('.format-tag').forEach(tag => {
    tag.addEventListener('click', function() {
        // Remove active class from all tags
        document.querySelectorAll('.format-tag').forEach(t => {
            t.classList.remove('bg-blue-600', 'text-white');
            t.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
        });
        
        // Add active class to clicked tag
        this.classList.add('bg-blue-600', 'text-white');
        this.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
        
        // Set format filter
        document.getElementById('format-filter').value = this.dataset.format;
        filterImages();
    });
});

// Sort functionality
document.getElementById('sort-filter').addEventListener('change', function() {
    sortImages(this.value);
});

// View mode functionality
document.getElementById('grid-view').addEventListener('click', function() {
    document.getElementById('images-grid').className = 'grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4';
    this.classList.add('bg-blue-600', 'text-white');
    this.classList.remove('bg-gray-200', 'text-gray-700');
    document.getElementById('list-view').classList.remove('bg-blue-600', 'text-white');
    document.getElementById('list-view').classList.add('bg-gray-200', 'text-gray-700');
});

document.getElementById('list-view').addEventListener('click', function() {
    document.getElementById('images-grid').className = 'space-y-4';
    this.classList.add('bg-blue-600', 'text-white');
    this.classList.remove('bg-gray-200', 'text-gray-700');
    document.getElementById('grid-view').classList.remove('bg-blue-600', 'text-white');
    document.getElementById('grid-view').classList.add('bg-gray-200', 'text-gray-700');
});

// Selection
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('image-checkbox')) {
        const imageId = e.target.value;
        if (e.target.checked) {
            selectedImages.add(imageId);
        } else {
            selectedImages.delete(imageId);
        }
        updateSelectionUI();
    }
});

function updateSelectedCount() {
    const checked = document.querySelectorAll('.image-checkbox:checked').length;
    const countElement = document.getElementById('selected-count');
    const downloadBtn = document.getElementById('batch-download-btn');
    
    countElement.textContent = checked;
    downloadBtn.disabled = checked === 0;
    
    if (checked > 0) {
        countElement.classList.remove('hidden');
    } else {
        countElement.classList.add('hidden');
    }
}

function filterImages() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const formatFilter = document.querySelector('.format-tag.bg-blue-600')?.dataset.format || '';
    
    document.querySelectorAll('.image-item').forEach(item => {
        const name = item.dataset.name;
        const format = item.dataset.format;
        
        const matchesSearch = name.includes(searchTerm);
        const matchesFormat = !formatFilter || format === formatFilter;
        
        if (matchesSearch && matchesFormat) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function sortImages(sortBy) {
    const container = document.getElementById('images-grid');
    const items = Array.from(container.querySelectorAll('.image-item'));
    
    items.sort((a, b) => {
        switch(sortBy) {
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

async function openImageModal(imageId) {
    const modal = document.getElementById('image-modal');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    
    // Mostrar modal com loading
    modal.classList.remove('hidden');
    modalTitle.textContent = 'Carregando...';
    modalContent.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
    `;
    
    try {
        // Buscar dados da imagem
        const response = await fetch(`/assets/${imageId}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Erro ao carregar imagem');
        }
        
        const asset = data.asset;
        
        // Atualizar título do modal
        modalTitle.textContent = asset.name;
        
        // Criar conteúdo do modal
        let dimensionsText = '';
        if (asset.dimensions) {
            dimensionsText = `<span class="text-gray-500 dark:text-gray-400">${asset.dimensions.width} × ${asset.dimensions.height}px</span>`;
        }
        
        let tagsHtml = '';
        if (asset.tags && asset.tags.length > 0) {
            tagsHtml = `
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tags:</h4>
                    <div class="flex flex-wrap gap-2">
                        ${asset.tags.map(tag => `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                ${tag}
                            </span>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        modalContent.innerHTML = `
            <div class="space-y-6">
                <!-- Imagem -->
                <div class="flex justify-center">
                    <img src="${asset.file_url}" 
                         alt="${asset.name}" 
                         class="max-w-full max-h-96 object-contain rounded-lg shadow-lg"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMDAgMTAwTDEwMCAxMDBaIiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiIvPgo8L3N2Zz4K'">
                </div>
                
                <!-- Informações da imagem -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">Nome:</span>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">${asset.name}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">Formato:</span>
                            <p class="text-gray-600 dark:text-gray-300 mt-1 uppercase">${asset.format}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">Tamanho:</span>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">${asset.file_size_formatted}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">Dimensões:</span>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">${dimensionsText || 'N/A'}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">Criado em:</span>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">${asset.created_at}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">Atualizado em:</span>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">${asset.updated_at}</p>
                        </div>
                    </div>
                    ${tagsHtml}
                </div>
                
                <!-- Botões de ação -->
                <div class="flex justify-center space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="${asset.file_url}" 
                       target="_blank"
                       class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors duration-200" 
                       title="Abrir Original">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button onclick="downloadImageInNewTab('${asset.id}')" 
                            class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600 dark:hover:text-green-400 rounded-lg transition-colors duration-200" 
                            title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                    <button onclick="openDeleteConfirmModal('${asset.id}')" 
                            class="inline-flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition-colors duration-200" 
                            title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
    } catch (error) {
        console.error('Erro ao carregar imagem:', error);
        modalTitle.textContent = 'Erro';
        modalContent.innerHTML = `
            <div class="text-center py-12">
                <div class="text-red-500 mb-4">
                    <i class="fas fa-exclamation-triangle text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Erro ao carregar imagem</h3>
                <p class="text-gray-500 dark:text-gray-400">${error.message}</p>
                <button onclick="closeImageModal()" 
                        class="mt-4 inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Fechar
                </button>
            </div>
        `;
    }
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
}

// Modal de confirmação de exclusão
function openDeleteConfirmModal(imageId) {
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = document.getElementById('delete-modal-content');
    const imageNameSpan = document.getElementById('delete-image-name');
    const confirmBtn = document.getElementById('confirm-delete-btn');
    
    // Buscar nome da imagem
    const imageItem = document.querySelector(`[data-id="${imageId}"]`);
    const imageName = imageItem ? imageItem.dataset.name : 'esta imagem';
    
    imageNameSpan.textContent = imageName;
    confirmBtn.onclick = () => confirmDelete(imageId);
    
    // Mostrar modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animar entrada após um pequeno delay
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteConfirmModal() {
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = document.getElementById('delete-modal-content');
    
    // Animar saída
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Ocultar modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function deleteImageFromModal(imageId) {
    openDeleteConfirmModal(imageId);
}

async function confirmDelete(imageId) {
    const confirmBtn = document.getElementById('confirm-delete-btn');
    const originalText = confirmBtn.innerHTML;
    
    try {
        // Mostrar loading no botão
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Excluindo...';
        confirmBtn.disabled = true;
        
        // Fazer requisição de exclusão
        const response = await fetch(`/assets/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Fechar modais
            closeDeleteConfirmModal();
            closeImageModal();
            
            // Remover item da lista
            const imageItem = document.querySelector(`[data-id="${imageId}"]`);
            if (imageItem) {
                imageItem.remove();
            }
            
            // Mostrar mensagem de sucesso
            showSuccessMessage('Imagem excluída com sucesso!');
            
            // Atualizar contador se necessário
            updateSelectedCount();
        } else {
            throw new Error(data.message || 'Erro ao excluir imagem');
        }
    } catch (error) {
        console.error('Erro ao excluir imagem:', error);
        showErrorMessage(error.message || 'Erro ao excluir imagem. Tente novamente.');
    } finally {
        // Restaurar botão
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    }
}

function deleteImage(imageId) {
    openDeleteConfirmModal(imageId);
}

// Funções para mostrar mensagens
function showSuccessMessage(message) {
    const toast = createToast(message, 'success');
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function showErrorMessage(message) {
    const toast = createToast(message, 'error');
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

function createToast(message, type) {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3 transform transition-all duration-300 translate-x-full`;
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    return toast;
}

// Função para baixar imagem
function downloadImage(imageId) {
    window.open(`/assets/${imageId}/download`, '_blank');
}

// Função para baixar imagem em nova guia e fechar automaticamente
function downloadImageInNewTab(imageId) {
    try {
        // Mostrar feedback visual
        showSuccessMessage('Iniciando download...');
        
        // Abrir nova guia para download
        const downloadWindow = window.open(`/assets/${imageId}/download`, '_blank');
        
        // Verificar se a janela foi aberta (pode ser bloqueada por popup blocker)
        if (!downloadWindow) {
            // Fallback: download direto na mesma guia
            window.location.href = `/assets/${imageId}/download`;
            return;
        }
        
        // Fechar a guia após um delay para permitir o download
        setTimeout(() => {
            try {
                if (downloadWindow && !downloadWindow.closed) {
                    downloadWindow.close();
                }
            } catch (error) {
                // Ignorar erros de fechamento (pode acontecer se o usuário já fechou)
                console.log('Guia já foi fechada pelo usuário');
            }
        }, 2500);
        
    } catch (error) {
        console.error('Erro ao iniciar download:', error);
        showErrorMessage('Erro ao iniciar download. Tente novamente.');
        
        // Fallback: download direto
        window.location.href = `/assets/${imageId}/download`;
    }
}

function updateSelectionUI() {
    const count = selectedImages.size;
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

// Batch Download
document.getElementById('batch-download-btn').addEventListener('click', async function() {
    if (selectedImages.size === 0) {
        alert('Nenhuma imagem selecionada');
        return;
    }

    const imageIds = Array.from(selectedImages);
    
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
                asset_ids: imageIds
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
                a.download = 'imagens.zip';
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

</script>
@endpush