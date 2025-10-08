@extends('layouts.app')

@section('title', 'Principais Parceiros')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="partnersPage()">
    <!-- Header com Tags de Navegação -->
    <div class="mb-6">
        <!-- Tags de Navegação Rápida -->
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-home mr-2"></i>
                Dashboard
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <i class="fas fa-handshake mr-2"></i>
                Principais Parceiros
            </span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Principais Parceiros</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie os parceiros exibidos na página inicial do seu portfólio</p>
                </div>
                

            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Parceiros -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Parceiros</p>
                    <p class="text-2xl font-bold">{{ $partners->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Parceiros Ativos -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Parceiros Ativos</p>
                    <p class="text-2xl font-bold">{{ $partners->where('is_active', true)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Parceiros com Logo -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Com Logo</p>
                    <p class="text-2xl font-bold">{{ $partners->whereNotNull('logo_path')->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Parceiros Inativos -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Parceiros Inativos</p>
                    <p class="text-2xl font-bold">{{ $partners->where('is_active', false)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                       placeholder="Buscar por nome ou descrição..." 
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

            <!-- Filtro de Status -->
            <div class="w-full md:w-48">
                <select id="status-filter" class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Todos os Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Grid de Cards de Parceiros -->
    @if($partners->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="partners-grid">
            @foreach($partners as $partner)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 group flex flex-col h-full" 
                     data-partner-id="{{ $partner->id }}" 
                     draggable="true">
                    <!-- Header do Card -->
                    <div class="p-6 flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3 flex-1">
                                <!-- Logo do Parceiro -->
                                <div class="h-12 w-12 rounded-lg flex items-center justify-center flex-shrink-0 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    @if($partner->logo_path)
                                        <img src="{{ $partner->logo_url }}" 
                                             alt="{{ $partner->name }}" 
                                             class="h-full w-full object-contain">
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 font-bold text-lg">{{ strtoupper(substr($partner->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $partner->name }}</h3>
                                    <div class="flex items-center space-x-2 mt-1">
                                        @if($partner->is_active)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Inativo
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Drag Handle -->
                            <div class="p-1 text-gray-400 cursor-move opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Descrição -->
                        @if($partner->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ $partner->description }}</p>
                        @endif
                        
                        <!-- Website -->
                        @if($partner->website_url)
                            <div class="mb-4">
                                <a href="{{ $partner->website_url }}" 
                                   target="_blank" 
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Visitar Website
                                </a>
                            </div>
                        @endif
                        
                        <!-- Estatísticas -->
                        <div class="grid grid-cols-1 gap-2 mb-4">
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $partner->sort_order }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Ordem de Exibição</div>
                            </div>
                        </div>
                        
                        <!-- Data de Criação -->
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                            Criado em {{ $partner->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    
                    <!-- Footer do Card com Ações -->
                    <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <!-- Ações com Ícones -->
                        <div class="flex space-x-3">
                            <a href="{{ route('partners.show', $partner) }}" 
                               class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                               title="Visualizar Parceiro">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('partners.edit', $partner) }}" 
                               class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-900/20" 
                               title="Editar Parceiro">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <!-- Toggle Status -->
                            <button type="button" 
                                    onclick="togglePartnerStatus({{ $partner->id }})" 
                                    class="p-2 rounded-lg transition-all duration-200 {{ $partner->is_active ? 'text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20' : 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20' }}" 
                                    title="{{ $partner->is_active ? 'Desativar Parceiro' : 'Ativar Parceiro' }}">
                                @if($partner->is_active)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </button>
                            <button onclick="deletePartner({{ $partner->id }}, '{{ addslashes($partner->name) }}')" 
                                    class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                    title="Excluir Parceiro">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum parceiro encontrado</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Comece criando seu primeiro parceiro para exibir na página inicial.</p>
            <a href="{{ route('partners.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Criar Primeiro Parceiro
            </a>
        </div>
    @endif

    <!-- Botão Flutuante de Criação -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="{{ route('partners.create') }}" 
           class="group fixed bottom-6 right-6 z-50 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center justify-center w-14 h-14"
           title="Adicionar Novo Parceiro">
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
                    Tem certeza que deseja excluir o parceiro <span id="partner-name" class="font-semibold"></span>? Esta ação não pode ser desfeita.
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
function partnersPage() {
    return {
        init() {
            this.initSearch();
            this.initFilters();
            this.initDragAndDrop();
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
                    this.filterPartners();
                });

                if (searchInput.value) {
                    clearButton.style.display = 'block';
                }
            }

            if (clearButton) {
                clearButton.addEventListener('click', () => {
                    searchInput.value = '';
                    clearButton.style.display = 'none';
                    this.filterPartners();
                });
            }
        },

        initFilters() {
            const statusFilter = document.getElementById('status-filter');
            if (statusFilter) {
                statusFilter.addEventListener('change', () => {
                    this.filterPartners();
                });
            }
        },

        filterPartners() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const statusFilter = document.getElementById('status-filter').value;
            const partnerCards = document.querySelectorAll('[data-partner-id]');

            partnerCards.forEach(card => {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p')?.textContent.toLowerCase() || '';
                const isActive = card.querySelector('.bg-green-100') !== null;
                
                let showCard = true;

                // Filtro de busca
                if (searchTerm && !name.includes(searchTerm) && !description.includes(searchTerm)) {
                    showCard = false;
                }

                // Filtro de status
                if (statusFilter === 'active' && !isActive) {
                    showCard = false;
                } else if (statusFilter === 'inactive' && isActive) {
                    showCard = false;
                }

                card.style.display = showCard ? 'block' : 'none';
            });
        },

        initDragAndDrop() {
            const grid = document.getElementById('partners-grid');
            if (!grid) return;

            let draggedElement = null;

            grid.addEventListener('dragstart', (e) => {
                if (e.target.closest('[data-partner-id]')) {
                    draggedElement = e.target.closest('[data-partner-id]');
                    e.target.style.opacity = '0.5';
                }
            });

            grid.addEventListener('dragend', (e) => {
                if (e.target.closest('[data-partner-id]')) {
                    e.target.style.opacity = '1';
                    draggedElement = null;
                }
            });

            grid.addEventListener('dragover', (e) => {
                e.preventDefault();
            });

            grid.addEventListener('drop', (e) => {
                e.preventDefault();
                const dropTarget = e.target.closest('[data-partner-id]');
                
                if (dropTarget && draggedElement && dropTarget !== draggedElement) {
                    const allCards = Array.from(grid.children);
                    const draggedIndex = allCards.indexOf(draggedElement);
                    const dropIndex = allCards.indexOf(dropTarget);
                    
                    if (draggedIndex < dropIndex) {
                        dropTarget.parentNode.insertBefore(draggedElement, dropTarget.nextSibling);
                    } else {
                        dropTarget.parentNode.insertBefore(draggedElement, dropTarget);
                    }
                    
                    this.updateOrder();
                }
            });
        },

        updateOrder() {
            const cards = document.querySelectorAll('[data-partner-id]');
            const order = Array.from(cards).map(card => card.dataset.partnerId);
            
            fetch('{{ route("partners.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Opcional: mostrar notificação de sucesso
                    console.log('Ordem atualizada com sucesso');
                }
            })
            .catch(error => {
                console.error('Erro ao atualizar ordem:', error);
            });
        }
    }
}

function togglePartnerStatus(partnerId) {
    fetch(`/partners/${partnerId}/toggle-status`, {
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
            alert('Erro ao alterar status do parceiro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao alterar status do parceiro');
    });
}



function showNotification(message, type = 'info') {
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function deletePartner(partnerId, partnerName) {
    const modal = document.getElementById('delete-modal');
    const partnerNameSpan = document.getElementById('partner-name');
    const confirmButton = document.getElementById('confirm-delete');
    const cancelButton = document.getElementById('cancel-delete');
    
    partnerNameSpan.textContent = partnerName;
    modal.classList.remove('hidden');
    
    confirmButton.onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/partners/${partnerId}`;
        
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