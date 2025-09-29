@extends('layouts.app')

@section('title', 'Asset Library')

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
            <div class="py-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Asset Library</h1>
                <p class="text-gray-600 dark:text-gray-400">Gerencie seus arquivos de imagem e fontes de forma centralizada</p>
            </div>
        </div>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Assets -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total de Assets</p>
                        <p class="text-3xl font-bold" id="total-assets">{{ $totalAssets }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-folder text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Imagens</p>
                        <p class="text-3xl font-bold" id="total-images">{{ $totalImages }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-image text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Fonts -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Fontes</p>
                        <p class="text-3xl font-bold" id="total-fonts">{{ $totalFonts }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-font text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Storage -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Armazenamento</p>
                        <p class="text-3xl font-bold" id="total-storage">{{ $totalStorage }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-hdd text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>



        <!-- Recent Assets -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Assets Recentes</h2>
            </div>
            <div class="p-6">
                @if(isset($recentAssets) && $recentAssets->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($recentAssets as $asset)
                            <div class="group relative bg-gray-50 dark:bg-gray-700 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <!-- Asset Preview -->
                                <div class="aspect-square mb-2 rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                                    @if($asset->type === 'image')
                                        <img src="{{ $asset->thumbnail_url }}" 
                                             alt="{{ $asset->original_name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-font text-2xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Asset Info -->
                                <div class="text-center">
                                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate" title="{{ $asset->original_name }}">
                                        {{ $asset->original_name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $asset->formatted_size }}
                                    </p>
                                </div>
                                
                                <!-- Quick Actions -->
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('assets.download', $asset->id) }}" 
                                       class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum asset encontrado</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Comece fazendo upload dos seus primeiros arquivos</p>
                        <a href="{{ route('assets.upload') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-upload mr-2"></i>
                            Fazer Upload
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="{{ route('assets.upload') }}" 
           class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <i class="fas fa-upload text-xl"></i>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/asset-library.js') }}"></script>
<script>
// Filtrar assets por tipo
function filterAssets(type) {
    // Redirecionar para a página de assets com filtro
    if (type === 'image') {
        window.location.href = '{{ route("assets.index") }}?type=image';
    } else if (type === 'font') {
        window.location.href = '{{ route("assets.index") }}?type=font';
    }
}

// Atualizar estatísticas em tempo real se necessário
function updateStats() {
    // Implementar se necessário
}

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    // Implementar funcionalidades adicionais se necessário
});
</script>
@endpush