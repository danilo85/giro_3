@extends('layouts.app')

@section('title', 'Orçamentos')

@section('content')
<div class="max-w-7xl mx-auto  px-4 py-6">

    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('dashboard') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v3H8V5z"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('orcamentos.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white dark:bg-blue-700 dark:text-blue-200">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Orçamentos
        </a>
        <a href="{{ route('clientes.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Clientes
        </a>
        <a href="{{ route('autores.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Autores
        </a>
        <a href="{{ route('pagamentos.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Pagamentos
        </a>
        <a href="{{ route('modelos-propostas.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Modelos de Propostas
        </a>
            <a href="{{ route('kanban.index') }}" 
           class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
            </svg>
            Kanban
        </a>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Orçamentos</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie todos os orçamentos do sistema</p>
            </div>
            
            <!-- Botão de Privacidade -->
            <div class="flex items-center space-x-4">
                <button id="privacyToggle" 
                        class="flex items-center p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
                        title="Alternar visibilidade dos valores">
                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>

                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total de Orçamentos</p>
                    <p class="text-2xl font-bold text-white">{{ $orcamentos->total() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Aprovados</p>
                    <p class="text-2xl font-bold text-white">{{ $orcamentos->where('status', 'aprovado')->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Pendentes</p>
                    <p class="text-2xl font-bold text-white">{{ $orcamentos->whereIn('status', ['rascunho', 'analisando'])->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white" id="valorReceberCard">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100">Valor a Receber</p>
                    <p class="text-2xl font-bold text-white currency-value" id="valorReceberAmount" data-original="R$ {{ number_format($valorRestanteReceber ?? 0, 2, ',', '.') }}">R$ {{ number_format($valorRestanteReceber ?? 0, 2, ',', '.') }}</p>
                    <p class="text-xs text-purple-200 mt-1" id="valorReceberDescription">Orçamentos válidos menos pagamentos</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <!-- Botão para limpar seleção (inicialmente oculto) -->
            <div id="clearSelectionButton" class="mt-4 hidden">
                <button onclick="clearSelection()" class="w-full px-3 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-white text-sm font-medium transition-all duration-200">
                    Limpar Seleção
                </button>
            </div>
        </div>
    </div>


    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <!-- Filtro de busca completo -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por cliente, título ou autor..."
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if(request()->filled('search'))
                        <button type="button" id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>

            </div>
        </div>
        
        <!-- Filtros rápidos por status -->
        <div class="p-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('orcamentos.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ !request('status') && !request('incluir_finalizados') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                    Todos
                </a>
                <a href="{{ route('orcamentos.index', ['incluir_finalizados' => '1']) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('incluir_finalizados') ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                    <span class="w-2 h-2 bg-indigo-400 rounded-full mr-1.5"></span>
                    Incluir Finalizados
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'rascunho'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'rascunho' ? 'bg-gray-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-1.5"></span>
                    Rascunho
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'analisando'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'analisando' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800' }}">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5"></span>
                    Analisando
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'rejeitado'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'rejeitado' ? 'bg-red-500 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800' }}">
                    <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                    Rejeitado
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'aprovado'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'aprovado' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800' }}">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                    Aprovado
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'finalizado'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'finalizado' ? 'bg-blue-500 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800' }}">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-1.5"></span>
                    Finalizado
                </a>

                <a href="{{ route('orcamentos.index', ['status' => 'quitado'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'quitado' ? 'bg-purple-500 text-white' : 'bg-purple-100 text-purple-700 hover:bg-purple-200 dark:bg-purple-900 dark:text-purple-300 dark:hover:bg-purple-800' }}">
                    <span class="w-2 h-2 bg-purple-400 rounded-full mr-1.5"></span>
                    Quitado
                </a>
            </div>
        </div>
    </div>

    <!-- Orçamentos List -->
    <div class="space-y-4">
        @if($orcamentos->count() > 0)
            <!-- Grid de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                @foreach($orcamentos as $orcamento)
                    @php
                        $cardColors = [
                            'rascunho' => [
                                'bg' => 'bg-gray-50 dark:bg-gray-800',
                                'border' => 'border-gray-200 dark:border-gray-600',
                                'hover' => 'hover:bg-gray-50 dark:hover:bg-gray-700',
                                'section' => 'bg-gray-100 dark:bg-gray-700',
                                'text' => 'text-gray-600 dark:text-gray-300'
                            ],
                            'analisando' => [
                                'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
                                'border' => 'border-yellow-200 dark:border-yellow-600',
                                'hover' => 'hover:bg-yellow-50 dark:hover:bg-yellow-200/30',
                                'section' => 'bg-yellow-100 dark:bg-yellow-800/40',
                                'text' => 'text-yellow-700 dark:text-yellow-300'
                            ],
                            'aprovado' => [
                                'bg' => 'bg-green-50 dark:bg-green-900/20',
                                'border' => 'border-green-200 dark:border-green-600',
                                'hover' => 'hover:bg-green-50 dark:hover:bg-green-800/30',
                                'section' => 'bg-green-100 dark:bg-green-800/40',
                                'text' => 'text-green-700 dark:text-green-300'
                            ],
                            'rejeitado' => [
                                'bg' => 'bg-red-50 dark:bg-red-900/20',
                                'border' => 'border-red-200 dark:border-red-600',
                                'hover' => 'hover:bg-red-50 dark:hover:bg-red-800/30',
                                'section' => 'bg-red-100 dark:bg-red-800/40',
                                'text' => 'text-red-700 dark:text-red-300'
                            ],
                            'finalizado' => [
                                'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                                'border' => 'border-blue-200 dark:border-blue-600',
                                'hover' => 'hover:bg-blue-50 dark:hover:bg-blue-800/30',
                                'section' => 'bg-blue-100 dark:bg-blue-800/40',
                                'text' => 'text-blue-700 dark:text-blue-300'
                            ],

                            'quitado' => [
                                'bg' => 'bg-purple-50 dark:bg-purple-900/20',
                                'border' => 'border-purple-200 dark:border-purple-600',
                                'hover' => 'hover:bg-purple-50 dark:hover:bg-purple-800/30',
                                'section' => 'bg-purple-100 dark:bg-purple-800/40',
                                'text' => 'text-purple-700 dark:text-purple-300'
                            ]
                        ];
                        $currentColors = $cardColors[$orcamento->status] ?? $cardColors['rascunho'];
                    @endphp
                    <div class="{{ $currentColors['bg'] }} rounded-lg shadow-sm border {{ $currentColors['border'] }} {{ $currentColors['hover'] }} transition-all duration-200 flex flex-col h-full relative hover:shadow-md orcamento-card" data-orcamento-id="{{ $orcamento->id }}" data-valor-total="{{ $orcamento->valor_total }}" data-saldo-restante="{{ $orcamento->saldo_restante }}">
                        

                        
                        <!-- Header do Card -->
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3 min-w-0 flex-1">
                                    @if($orcamento->cliente->avatar)
                                        <img src="{{ Storage::url($orcamento->cliente->avatar) }}" 
                                             alt="{{ $orcamento->cliente->nome }}" 
                                             class="w-12 h-12 rounded-full object-cover flex-shrink-0 mr-3">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                                            <span class="text-white font-semibold text-lg">
                                                {{ strtoupper(substr($orcamento->cliente->nome, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate" title="{{ $orcamento->titulo }}">
                                            {{ Str::limit($orcamento->titulo, 30) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status and Number Badges -->
                            <div class="flex justify-center items-center gap-2 mb-4">
                                <!-- Number Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    #{{ $orcamento->id }}
                                </span>
                                
                                <!-- Status Badge with Dropdown -->
                                @php
                                    $statusColors = [
                                        'rascunho' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'analisando' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'rejeitado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        'finalizado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'pago' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'quitado' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
                                    ];
                                    $allStatuses = [
                                        'rascunho' => 'Rascunho',
                                        'analisando' => 'Analisando',
                                        'aprovado' => 'Aprovado',
                                        'rejeitado' => 'Rejeitado',
                                        'finalizado' => 'Finalizado',
                                        'quitado' => 'Quitado'
                                    ];
                                @endphp
                                <div class="relative inline-block status-dropdown" data-orcamento-id="{{ $orcamento->id }}">
                                    <!-- Current Status Badge -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium cursor-pointer transition-all duration-200 hover:shadow-md {{ $statusColors[$orcamento->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($orcamento->status) }}
                                        <svg class="w-3 h-3 ml-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    
                                    <!-- Dropdown Menu -->
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-1 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 opacity-0 invisible transition-all duration-200 z-50 dropdown-menu">
                                        @foreach($allStatuses as $statusKey => $statusLabel)
                                            @if($statusKey !== $orcamento->status)
                                                @php
                                                    $textColors = [
                                                        'rascunho' => 'text-gray-800 dark:text-gray-300',
                                                        'analisando' => 'text-yellow-800 dark:text-yellow-200',
                                                        'aprovado' => 'text-green-800 dark:text-green-200',
                                                        'rejeitado' => 'text-red-800 dark:text-red-200',
                                                        'finalizado' => 'text-blue-800 dark:text-blue-200',
                                                        'quitado' => 'text-purple-800 dark:text-purple-200'
                                                    ];
                                                @endphp
                                                <button type="button" 
                                                        class="w-full text-left px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 hover:bg-gray-50 dark:hover:bg-gray-700 status-option {{ $textColors[$statusKey] }}" 
                                                        data-status="{{ $statusKey }}"
                                                        data-orcamento-id="{{ $orcamento->id }}">
                                                    {{ $statusLabel }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                

                            </div>
                        </div>

                        <!-- Detalhes do Orçamento -->
                        <div class="px-6 pb-6">
                            <div class="space-y-3">
                                
                                <!-- Valor e Data -->
                                <div class="{{ $currentColors['section'] }} rounded-lg p-4 border {{ $currentColors['border'] }}">
                                    <!-- Valor Total -->
                                    <div class="text-center mb-3">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Valor Total</p>
                                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 currency-value" data-original="R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}">
                                            R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    
                                    <!-- Informações de Pagamento -->
                                    @if($orcamento->valor_pago > 0)
                                        <div class="flex justify-between items-center text-xs space-y-1">
                                            <div class="flex flex-col items-start">
                                                <span class="text-gray-500 dark:text-gray-400">Pago:</span>
                                                <span class="font-medium text-blue-600 dark:text-blue-400 currency-value" data-original="R$ {{ number_format($orcamento->valor_pago, 2, ',', '.') }}">
                                                    R$ {{ number_format($orcamento->valor_pago, 2, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="flex flex-col items-end">
                                                <span class="text-gray-500 dark:text-gray-400">Restante:</span>
                                                <span class="font-medium {{ $orcamento->saldo_restante > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }} currency-value" data-original="R$ {{ number_format($orcamento->saldo_restante, 2, ',', '.') }}">
                                                    R$ {{ number_format($orcamento->saldo_restante, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Barra de Progresso -->
                                        @php
                                            $percentualPago = $orcamento->valor_total > 0 ? ($orcamento->valor_pago / $orcamento->valor_total) * 100 : 0;
                                        @endphp
                                        <div class="mt-2">
                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ min($percentualPago, 100) }}%"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">
                                                {{ number_format($percentualPago, 1) }}% pago
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Data de Criação -->
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Criado em {{ $orcamento->created_at->format('d/m/Y') }} às {{ $orcamento->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Flex grow para empurrar os botões para o rodapé -->
                        <div class="flex-grow"></div>

                        <!-- Autores e Actions Footer -->
                        <div class="px-6 py-4 border-t {{ $currentColors['border'] }} mt-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex -space-x-2">
                                    @foreach($orcamento->autores->take(3) as $autor)
                                        @if($autor->avatar)
                                            <img src="{{ Storage::url($autor->avatar) }}" 
                                                 alt="{{ $autor->nome }}" 
                                                 title="{{ $autor->nome }}"
                                                 class="h-8 w-8 rounded-full object-cover border-2 border-white dark:border-gray-800">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center border-2 border-white dark:border-gray-800" 
                                                 title="{{ $autor->nome }}">
                                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ substr($autor->nome, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($orcamento->autores->count() > 3)
                                        <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center border-2 border-white dark:border-gray-800">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">+{{ $orcamento->autores->count() - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                                <!-- Avatar do Cliente -->
                                <div class="flex items-center space-x-2">
                                    @if($orcamento->cliente->avatar)
                                        <img src="{{ Storage::url($orcamento->cliente->avatar) }}" 
                                             alt="{{ $orcamento->cliente->nome }}" 
                                             title="{{ $orcamento->cliente->nome }}"
                                             class="w-8 h-8 rounded-full object-cover border-2 border-white dark:border-gray-800">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center border-2 border-white dark:border-gray-800" 
                                             title="{{ $orcamento->cliente->nome }}">
                                            <span class="text-white font-semibold text-xs">
                                                {{ strtoupper(substr($orcamento->cliente->nome, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-3">
                                    <a href="{{ route('orcamentos.show', $orcamento) }}" 
                                       class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                                       title="Visualizar Orçamento">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('orcamentos.edit', $orcamento) }}" 
                                       class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                       title="Editar Orçamento">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('orcamentos.historico.index', $orcamento) }}" 
                                       class="p-2 rounded-lg text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 transition-all duration-200 hover:bg-purple-50 dark:hover:bg-purple-900/20" 
                                       title="Histórico do Projeto">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="sharePublicLink('{{ $orcamento->public_url }}')" 
                                            class="p-2 rounded-lg text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-all duration-200 hover:bg-green-50 dark:hover:bg-green-900/20" 
                                            title="Compartilhar Link Público">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex space-x-3">
                                    <button onclick="deleteOrcamento({{ $orcamento->id }})" 
                                            class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                            title="Excluir Orçamento">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($orcamentos->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    Anterior
                                </span>
                            @else
                                <a href="{{ $orcamentos->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Anterior
                                </a>
                            @endif
                            
                            @if($orcamentos->hasMorePages())
                                <a href="{{ $orcamentos->nextPageUrl() }}" class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Próximo
                                </a>
                            @else
                                <span class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    Próximo
                                </span>
                            @endif
                        </div>
                        
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Mostrando
                                    <span class="font-medium">{{ $orcamentos->firstItem() ?? 0 }}</span>
                                    até
                                    <span class="font-medium">{{ $orcamentos->lastItem() ?? 0 }}</span>
                                    de
                                    <span class="font-medium">{{ $orcamentos->total() }}</span>
                                    resultados
                                </p>
                            </div>
                            <div>
                                {{ $orcamentos->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum orçamento encontrado</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        @if(request()->hasAny(['search', 'status', 'cliente_id', 'periodo']))
                            Tente ajustar os filtros ou
                            <a href="{{ route('orcamentos.index') }}" class="text-blue-600 hover:text-blue-500">limpar a busca</a>.
                        @else
                            Comece criando seu primeiro orçamento para seus clientes.
                        @endif
                    </p>
                    <a href="{{ route('orcamentos.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        @if(request()->hasAny(['search', 'status', 'cliente_id', 'periodo']))
                            Novo Orçamento
                        @else
                            Criar Primeiro Orçamento
                        @endif
                    </a>
                </div>
            </div>
        @endif
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

<!-- Botão Flutuante -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('orcamentos.create') }}" 
       class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
    </a>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-[10003]">
    <div class="mx-4 sm:mx-auto p-5  w-full sm:w-96 max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Confirmar Exclusão</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja excluir este orçamento? Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Excluir
                    </button>
                </form>
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteOrcamento(id) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/orcamentos/${id}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const searchToggle = document.getElementById('searchToggle');
    const searchContainer = document.getElementById('searchContainer');
    const searchInput = document.getElementById('search');
    const clearSearch = document.getElementById('clearSearch');
    const closeSearch = document.getElementById('closeSearch');
    
    // Toggle da busca
    if (searchToggle && searchContainer) {
        searchToggle.addEventListener('click', function() {
            searchContainer.classList.toggle('hidden');
            if (!searchContainer.classList.contains('hidden')) {
                setTimeout(() => searchInput?.focus(), 100);
            }
        });
    }
    
    // Fechar busca
    if (closeSearch && searchContainer) {
        closeSearch.addEventListener('click', function() {
            searchContainer.classList.add('hidden');
            if (searchInput) {
                searchInput.value = '';
            }
        });
    }
    
    // Limpar busca
    if (clearSearch && searchInput) {
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            const searchForm = searchInput.closest('form');
            if (searchForm) {
                searchForm.submit();
            }
        });
    }
    
    // Busca automática
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchForm = this.closest('form');
                if (searchForm) {
                    searchForm.submit();
                }
            }, 500);
        });
    }
    
    // Status Dropdown Functionality
    const statusDropdowns = document.querySelectorAll('.status-dropdown');
    
    statusDropdowns.forEach(dropdown => {
        const badge = dropdown.querySelector('span');
        const menu = dropdown.querySelector('.dropdown-menu');
        const arrow = dropdown.querySelector('svg');
        let hoverTimeout;
        
        // Store original badge state for error recovery
        dropdown.dataset.originalClasses = badge.className;
        dropdown.dataset.originalText = badge.innerHTML;
        
        // Show dropdown on hover
        dropdown.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
            menu.classList.remove('opacity-0', 'invisible');
            menu.classList.add('opacity-100', 'visible');
            arrow.style.transform = 'rotate(180deg)';
        });
        
        // Hide dropdown when leaving
        dropdown.addEventListener('mouseleave', function() {
            hoverTimeout = setTimeout(() => {
                menu.classList.remove('opacity-100', 'visible');
                menu.classList.add('opacity-0', 'invisible');
                arrow.style.transform = 'rotate(0deg)';
            }, 150);
        });
        
        // Handle status option clicks
        const statusOptions = dropdown.querySelectorAll('.status-option');
        statusOptions.forEach(option => {
            option.addEventListener('click', function() {
                const newStatus = this.dataset.status;
                const orcamentoId = this.dataset.orcamentoId;
                
                // Show loading state
                badge.innerHTML = '<div class="flex items-center"><div class="animate-spin rounded-full h-3 w-3 border-b-2 border-current mr-1"></div>Atualizando...</div>';
                
                // Update status via AJAX
                updateOrcamentoStatus(orcamentoId, newStatus, dropdown);
            });
        });
    });
});

// Function to update orcamento status
function updateOrcamentoStatus(orcamentoId, newStatus, dropdownElement) {
    fetch(`/orcamentos/${orcamentoId}/update-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update badge dynamically without reload
            updateBadgeDisplay(dropdownElement, newStatus);
            
            // Update card colors dynamically
            updateCardColors(dropdownElement, newStatus);
            
            // Hide dropdown
            const menu = dropdownElement.querySelector('.dropdown-menu');
            const arrow = dropdownElement.querySelector('svg');
            menu.classList.remove('opacity-100', 'visible');
            menu.classList.add('opacity-0', 'invisible');
            arrow.style.transform = 'rotate(0deg)';
            
            // Show success feedback
            showStatusUpdateFeedback(dropdownElement, 'success');
            
            // Atualizar card financeiro se o status mudou para/de aprovado ou quitado
            const statusesFinanceiros = ['aprovado', 'quitado'];
            const statusAnterior = dropdownElement.dataset.originalStatus || dropdownElement.closest('.status-dropdown').querySelector('span').textContent.toLowerCase().trim();
            
            if (statusesFinanceiros.includes(newStatus) || statusesFinanceiros.includes(statusAnterior)) {
                atualizarValorFinanceiro();
            }
        } else {
            throw new Error(data.message || 'Erro ao atualizar status');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        
        // Restore original badge state
        restoreOriginalBadge(dropdownElement);
        
        // Show error feedback
        showStatusUpdateFeedback(dropdownElement, 'error', error.message);
    });
}

// Function to update badge display
function updateBadgeDisplay(dropdownElement, newStatus) {
    const badge = dropdownElement.querySelector('span');
    const statusColors = {
        'rascunho': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        'analisando': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'aprovado': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'rejeitado': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'finalizado': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'quitado': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
    };
    const statusLabels = {
        'rascunho': 'Rascunho',
        'analisando': 'Analisando',
        'aprovado': 'Aprovado',
        'rejeitado': 'Rejeitado',
        'finalizado': 'Finalizado',
        'quitado': 'Quitado'
    };
    
    // Remove all status classes
    Object.values(statusColors).forEach(colorClass => {
        colorClass.split(' ').forEach(cls => badge.classList.remove(cls));
    });
    
    // Add new status classes
    const newColorClasses = statusColors[newStatus] || 'bg-gray-100 text-gray-800';
    newColorClasses.split(' ').forEach(cls => badge.classList.add(cls));
    
    // Update badge text
    badge.innerHTML = `${statusLabels[newStatus] || newStatus} <svg class="w-3 h-3 ml-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>`;
}

// Function to restore original badge state
function restoreOriginalBadge(dropdownElement) {
    const badge = dropdownElement.querySelector('span');
    const originalStatus = dropdownElement.dataset.originalStatus;
    const originalText = dropdownElement.dataset.originalText;
    const originalClasses = dropdownElement.dataset.originalClasses;
    
    if (originalClasses && originalText) {
        badge.className = originalClasses;
        badge.innerHTML = originalText;
    }
}

// Function to update card colors dynamically
function updateCardColors(dropdownElement, newStatus) {
    // Find the card container (parent div with card classes)
    const card = dropdownElement.closest('div[class*="bg-"]');
    if (!card) return;
    
    // Define color mappings (same as PHP)
    const cardColors = {
        'rascunho': {
            'bg': 'bg-gray-50 dark:bg-gray-800',
            'border': 'border-gray-200 dark:border-gray-600',
            'hover': 'hover:bg-gray-50 dark:hover:bg-gray-700',
            'section': 'bg-gray-100 dark:bg-gray-700',
            'text': 'text-gray-600 dark:text-gray-300'
        },
        'analisando': {
            'bg': 'bg-yellow-50 dark:bg-yellow-900/20',
            'border': 'border-yellow-200 dark:border-yellow-600',
            'hover': 'hover:bg-yellow-50 dark:hover:bg-yellow-200/30',
            'section': 'bg-yellow-100 dark:bg-yellow-800/40',
            'text': 'text-yellow-700 dark:text-yellow-300'
        },
        'aprovado': {
            'bg': 'bg-green-50 dark:bg-green-900/20',
            'border': 'border-green-200 dark:border-green-600',
            'hover': 'hover:bg-green-50 dark:hover:bg-green-800/30',
            'section': 'bg-green-100 dark:bg-green-800/40',
            'text': 'text-green-700 dark:text-green-300'
        },
        'rejeitado': {
            'bg': 'bg-red-50 dark:bg-red-900/20',
            'border': 'border-red-200 dark:border-red-600',
            'hover': 'hover:bg-red-50 dark:hover:bg-red-800/30',
            'section': 'bg-red-100 dark:bg-red-800/40',
            'text': 'text-red-700 dark:text-red-300'
        },
        'finalizado': {
            'bg': 'bg-blue-50 dark:bg-blue-900/20',
            'border': 'border-blue-200 dark:border-blue-600',
            'hover': 'hover:bg-blue-50 dark:hover:bg-blue-800/30',
            'section': 'bg-blue-100 dark:bg-blue-800/40',
            'text': 'text-blue-700 dark:text-blue-300'
        },
        'quitado': {
            'bg': 'bg-purple-50 dark:bg-purple-900/20',
            'border': 'border-purple-200 dark:border-purple-600',
            'hover': 'hover:bg-purple-50 dark:hover:bg-purple-800/30',
            'section': 'bg-purple-100 dark:bg-purple-800/40',
            'text': 'text-purple-700 dark:text-purple-300'
        }
    };
    
    const newColors = cardColors[newStatus] || cardColors['rascunho'];
    const allColors = Object.values(cardColors);
    
    // Remove all old color classes from card
    allColors.forEach(colorSet => {
        Object.values(colorSet).forEach(classString => {
            classString.split(' ').forEach(cls => {
                if (cls.trim()) card.classList.remove(cls.trim());
            });
        });
    });
    
    // Add new color classes to card
    newColors.bg.split(' ').forEach(cls => {
        if (cls.trim()) card.classList.add(cls.trim());
    });
    newColors.border.split(' ').forEach(cls => {
        if (cls.trim()) card.classList.add(cls.trim());
    });
    newColors.hover.split(' ').forEach(cls => {
        if (cls.trim()) card.classList.add(cls.trim());
    });
    
    // Update section colors (client info and value sections)
    const sections = card.querySelectorAll('div[class*="bg-"][class*="rounded-lg"][class*="p-4"]');
    sections.forEach(section => {
        // Remove old section colors
        allColors.forEach(colorSet => {
            colorSet.section.split(' ').forEach(cls => {
                if (cls.trim()) section.classList.remove(cls.trim());
            });
            colorSet.border.split(' ').forEach(cls => {
                if (cls.trim()) section.classList.remove(cls.trim());
            });
        });
        
        // Add new section colors
        newColors.section.split(' ').forEach(cls => {
            if (cls.trim()) section.classList.add(cls.trim());
        });
        newColors.border.split(' ').forEach(cls => {
            if (cls.trim()) section.classList.add(cls.trim());
        });
    });
    
    // Update footer border
    const footer = card.querySelector('.border-t');
    if (footer) {
        // Remove old border colors
        allColors.forEach(colorSet => {
            colorSet.border.split(' ').forEach(cls => {
                if (cls.trim()) footer.classList.remove(cls.trim());
            });
        });
        
        // Add new border color
        newColors.border.split(' ').forEach(cls => {
            if (cls.trim()) footer.classList.add(cls.trim());
        });
    }
}

// Function to show status update feedback
function showStatusUpdateFeedback(dropdownElement, type, message = '') {
    const badge = dropdownElement.querySelector('span');
    const originalContent = badge.innerHTML;
    
    if (type === 'success') {
        const tempContent = badge.innerHTML;
        badge.innerHTML = '<div class="flex items-center"><svg class="w-3 h-3 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Atualizado!</div>';
        setTimeout(() => {
            badge.innerHTML = tempContent;
        }, 2000);
    } else if (type === 'error') {
        alert('Erro ao atualizar o status: ' + message);
    }
}

// Function to update financial card value
function atualizarValorFinanceiro() {
    const valorCard = document.querySelector('[data-financial-card]');
    if (!valorCard) return;
    
    // Show loading state
    const valorElement = valorCard.querySelector('.text-2xl');
    const originalValue = valorElement.textContent;
    valorElement.innerHTML = '<div class="flex items-center justify-center"><div class="animate-spin rounded-full h-5 w-5 border-b-2 border-green-600 mr-2"></div>Atualizando...</div>';
    
    fetch('/orcamentos/valor-financeiro', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            valorElement.textContent = data.valor_formatado;
        } else {
            throw new Error(data.message || 'Erro ao buscar valor financeiro');
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar valor financeiro:', error);
        valorElement.textContent = originalValue;
    });
}

// Auto search functionality with debounce
let searchTimeout;
const searchInput = document.getElementById('search');
const clearButton = document.getElementById('clearSearch');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            const searchValue = this.value.trim();
            const currentUrl = new URL(window.location);
            
            if (searchValue) {
                currentUrl.searchParams.set('search', searchValue);
            } else {
                currentUrl.searchParams.delete('search');
            }
            
            // Preserve other parameters like status
            window.location.href = currentUrl.toString();
        }, 500); // 500ms debounce
    });
}

if (clearButton) {
    clearButton.addEventListener('click', function() {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('search');
        window.location.href = currentUrl.toString();
    });
}

// Variáveis para seleção múltipla de orçamentos
let selectedOrcamentos = new Set();
let originalValorReceber = {{ $valorRestanteReceber ?? 0 }};
let isSelectionMode = false;

// Inicializar seleção múltipla
function initializeOrcamentoSelection() {
    const cards = document.querySelectorAll('.orcamento-card');
    
    cards.forEach(card => {
        // Duplo clique para selecionar/deselecionar
        card.addEventListener('dblclick', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleOrcamentoSelection(this);
        });
    });

    // Duplo clique fora dos cards para limpar seleção
    document.addEventListener('dblclick', function(e) {
        if (!e.target.closest('.orcamento-card') && !e.target.closest('#valorReceberCard')) {
            clearSelection();
        }
    });
}

// Alternar seleção de orçamento
function toggleOrcamentoSelection(card) {
    const orcamentoId = card.dataset.orcamentoId;
    const valorTotal = parseFloat(card.dataset.valorTotal || 0);
    const saldoRestante = parseFloat(card.dataset.saldoRestante || 0);

    if (selectedOrcamentos.has(orcamentoId)) {
        // Deselecionar
        selectedOrcamentos.delete(orcamentoId);
        card.classList.remove('orcamento-selected');
    } else {
        // Selecionar
        selectedOrcamentos.add(orcamentoId);
        card.classList.add('orcamento-selected');
    }

    updateValorReceberCard();
}

// Atualizar card "Valor a Receber"
function updateValorReceberCard() {
    const valorReceberAmount = document.getElementById('valorReceberAmount');
    const valorReceberDescription = document.getElementById('valorReceberDescription');
    const clearButton = document.getElementById('clearSelectionButton');

    if (selectedOrcamentos.size > 0) {
        // Calcular soma dos orçamentos selecionados
        let somaTotal = 0;
        let somaSaldoRestante = 0;

        selectedOrcamentos.forEach(orcamentoId => {
            const card = document.querySelector(`[data-orcamento-id="${orcamentoId}"]`);
            if (card) {
                somaTotal += parseFloat(card.dataset.valorTotal || 0);
                somaSaldoRestante += parseFloat(card.dataset.saldoRestante || 0);
            }
        });

        // Atualizar valores
        valorReceberAmount.textContent = `R$ ${somaSaldoRestante.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        valorReceberDescription.textContent = `${selectedOrcamentos.size} orçamento(s) selecionado(s)`;
        clearButton.classList.remove('hidden');
        isSelectionMode = true;
    } else {
        // Restaurar valores originais
        valorReceberAmount.textContent = `R$ ${originalValorReceber.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        valorReceberDescription.textContent = 'Orçamentos válidos menos pagamentos';
        clearButton.classList.add('hidden');
        isSelectionMode = false;
    }
}

// Limpar seleção
function clearSelection() {
    selectedOrcamentos.clear();
    
    // Remover classe de seleção de todos os cards
    document.querySelectorAll('.orcamento-selected').forEach(card => {
        card.classList.remove('orcamento-selected');
    });

    updateValorReceberCard();
}

// Funcionalidade de Privacidade dos Valores
class PrivacyManager {
    constructor() {
        this.isPrivacyMode = this.getPrivacyState();
        this.init();
    }

    init() {
        this.bindEvents();
        this.applyPrivacyState();
    }

    bindEvents() {
        const privacyToggle = document.getElementById('privacyToggle');
        if (privacyToggle) {
            privacyToggle.addEventListener('click', () => {
                this.togglePrivacy();
            });
        }
    }

    togglePrivacy() {
        this.isPrivacyMode = !this.isPrivacyMode;
        this.savePrivacyState();
        this.applyPrivacyState();
    }

    applyPrivacyState() {
        const currencyElements = document.querySelectorAll('.currency-value');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        currencyElements.forEach(element => {
            if (this.isPrivacyMode) {
                // Salvar valor original se não existir
                if (!element.dataset.original) {
                    element.dataset.original = element.textContent;
                }
                // Aplicar máscara
                element.textContent = 'R$ ***';
                element.classList.add('privacy-hidden');
            } else {
                // Restaurar valor original
                if (element.dataset.original) {
                    element.textContent = element.dataset.original;
                }
                element.classList.remove('privacy-hidden');
            }
        });

        // Atualizar ícones do botão
        if (this.isPrivacyMode) {
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }

    getPrivacyState() {
        return localStorage.getItem('orcamentos_privacy_mode') === 'true';
    }

    savePrivacyState() {
        localStorage.setItem('orcamentos_privacy_mode', this.isPrivacyMode.toString());
    }

    // Método para atualizar valores dinamicamente (para seleção múltipla)
    updateDynamicValue(elementId, newValue) {
        const element = document.getElementById(elementId);
        if (element) {
            element.dataset.original = newValue;
            if (!this.isPrivacyMode) {
                element.textContent = newValue;
            }
        }
    }
}

// Instanciar o gerenciador de privacidade
let privacyManager;

// Modificar a função updateValorReceberCard para trabalhar com privacidade
function updateValorReceberCard() {
    const valorReceberAmount = document.getElementById('valorReceberAmount');
    const valorReceberDescription = document.getElementById('valorReceberDescription');
    const clearButton = document.getElementById('clearSelectionButton');

    if (selectedOrcamentos.size > 0) {
        // Calcular soma dos orçamentos selecionados
        let somaTotal = 0;
        let somaSaldoRestante = 0;

        selectedOrcamentos.forEach(orcamentoId => {
            const card = document.querySelector(`[data-orcamento-id="${orcamentoId}"]`);
            if (card) {
                somaTotal += parseFloat(card.dataset.valorTotal || 0);
                somaSaldoRestante += parseFloat(card.dataset.saldoRestante || 0);
            }
        });

        // Atualizar valores
        const newValue = `R$ ${somaSaldoRestante.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        
        // Usar o gerenciador de privacidade para atualizar o valor
        if (privacyManager) {
            privacyManager.updateDynamicValue('valorReceberAmount', newValue);
        } else {
            valorReceberAmount.textContent = newValue;
        }
        
        valorReceberDescription.textContent = `${selectedOrcamentos.size} orçamento(s) selecionado(s)`;
        clearButton.classList.remove('hidden');
        isSelectionMode = true;
    } else {
        // Restaurar valores originais
        const originalValue = `R$ ${originalValorReceber.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        
        // Usar o gerenciador de privacidade para restaurar o valor
        if (privacyManager) {
            privacyManager.updateDynamicValue('valorReceberAmount', originalValue);
        } else {
            valorReceberAmount.textContent = originalValue;
        }
        
        valorReceberDescription.textContent = 'Orçamentos válidos menos pagamentos';
        clearButton.classList.add('hidden');
        isSelectionMode = false;
    }
}

// Função para compartilhar link público do orçamento
function sharePublicLink(publicUrl) {
    // Copiar para área de transferência
    navigator.clipboard.writeText(publicUrl).then(function() {
        // Mostrar notificação de sucesso
        showShareNotification('Link copiado para a área de transferência!', 'success');
    }).catch(function(err) {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = publicUrl;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        showShareNotification('Link copiado para a área de transferência!', 'success');
    });
}

// Função para mostrar notificação de compartilhamento
function showShareNotification(message, type = 'success') {
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        notification.className += ' bg-green-500 text-white';
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                ${message}
            </div>
        `;
    } else {
        notification.className += ' bg-red-500 text-white';
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                ${message}
            </div>
        `;
    }
    
    // Adicionar ao DOM
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeOrcamentoSelection();
    privacyManager = new PrivacyManager();
});
</script>

<style>
/* Estilos para cards selecionados */
.orcamento-selected {
    border: 3px solid #8b5cf6 !important;
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.4) !important;
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(168, 85, 247, 0.1)) !important;
    transform: scale(1.02);
    position: relative;
}

.orcamento-selected::before {
    content: '✓';
    position: absolute;
    top: 10px;
    right: 10px;
    background: #8b5cf6;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    z-index: 10;
    animation: checkmarkPulse 0.3s ease-out;
}

@keyframes checkmarkPulse {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Transição suave para cards */
.orcamento-card {
    transition: all 0.3s ease;
}

/* Estilo para o botão de limpar seleção */
#clearSelectionButton button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Animação suave para transições */
.currency-value {
    transition: all 0.2s ease;
}
</style>

@endpush
@endsection