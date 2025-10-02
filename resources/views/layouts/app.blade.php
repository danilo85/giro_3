<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistema de Orçamentos') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
    
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        .backdrop {
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Loading Screen Styles */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255,1);
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease-out;
        }
        
        .dark #loading-screen {
            background-color: rgba(17, 24, 39, 0.95);
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .dark .loading-spinner {
            border-color: #374151;
            border-top-color: #60a5fa;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }
        
        .dark .loading-text {
            color: #9ca3af;
        }
        
        .loading-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        /* Mobile layout fixes */
        @media (max-width: 1023px) {
            .flex-1 {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .container, .recibo-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 0.5rem !important;
            }
        }
        
        @media (max-width: 480px) {
            main {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            .container, .recibo-container {
                padding: 0.25rem !important;
            }
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900" x-data="{}" x-init="$store.sidebar.init()">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Carregando...</div>
        </div>
    </div>
    
    <div id="app" class="min-h-full">
        <!-- Mobile Header -->
        <div class="lg:hidden bg-white dark:bg-gray-800 fixed top-0 left-0 right-0 z-40 h-16 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between h-full px-4">
                <button @click="$store.sidebar.toggle()" class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex-1"></div> <!-- Spacer -->
                <button id="theme-toggle-mobile" 
                        onclick="toggleTheme()"
                        class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg id="theme-toggle-dark-icon-mobile" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon-mobile" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L13.09 8.26L20 9L14 14.74L15.18 21.02L10 17.77L4.82 21.02L6 14.74L0 9L6.91 8.26L10 2Z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile sidebar backdrop -->
        <div x-show="$store.sidebar.open && $store.sidebar.isMobile" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"
             @click="$store.sidebar.close()"></div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out"
             :class="{
                 'w-64': !$store.sidebar.collapsed,
                 'w-16': $store.sidebar.collapsed,
                 'translate-x-0': $store.sidebar.open || !$store.sidebar.isMobile,
                 '-translate-x-full': !$store.sidebar.open && $store.sidebar.isMobile
             }"
             x-show="!$store.sidebar.isMobile || $store.sidebar.open">
            <div class="flex flex-col h-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
                <!-- Close Button (Mobile only) -->
                <div class="flex justify-end p-4" x-show="$store.sidebar.isMobile">
                    <button @click="$store.sidebar.close()" 
                            class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Navigation -->
                 <nav class="flex-1 px-2 space-y-1 overflow-y-auto" :class="$store.sidebar.isMobile ? 'py-2' : 'py-4'">
                     <!-- Collapse Button (Desktop only) -->
                     <div class="flex justify-end mb-4" x-show="!$store.sidebar.isMobile">
                         <button @click="$store.sidebar.toggle()" 
                                 class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                 :class="{ 'mx-auto': $store.sidebar.collapsed }">
                             <svg class="w-5 h-5 transform transition-transform" 
                                  :class="{ 'rotate-180': $store.sidebar.collapsed }" 
                                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                             </svg>
                         </button>
                     </div>
                     
                     <!-- User Avatar and Info -->
                     <div class="flex flex-col items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700" :class="{ 'justify-center': $store.sidebar.collapsed && !$store.sidebar.isMobile, 'px-4': $store.sidebar.isMobile }">
                         <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                             <!-- Avatar -->
                             @if(auth()->check() && auth()->user()->avatar_url)
                                 <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" :class="$store.sidebar.collapsed && !$store.sidebar.isMobile ? 'w-12 h-12' : 'w-16 h-16'" class="rounded-full object-cover flex-shrink-0" style="aspect-ratio: 1/1;">
                             @else
                                 <div :class="$store.sidebar.collapsed && !$store.sidebar.isMobile ? 'w-12 h-12' : 'w-16 h-16'" class="bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                     <span class="text-white font-bold" :class="$store.sidebar.collapsed && !$store.sidebar.isMobile ? 'text-lg' : 'text-xl'">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}</span>
                                 </div>
                             @endif
                             
                             <!-- User Name with Star if Admin -->
                             <div x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                  x-transition:enter="transition ease-in-out duration-150"
                                  x-transition:enter-start="opacity-0 transform scale-95"
                                  x-transition:enter-end="opacity-100 transform scale-100"
                                  x-transition:leave="transition ease-in-out duration-150"
                                  x-transition:leave-start="opacity-100 transform scale-100"
                                  x-transition:leave-end="opacity-0 transform scale-95"
                                  class="mt-3 text-center">
                                 <span class="text-sm font-medium text-gray-900 dark:text-white">
                                     {{ auth()->check() ? auth()->user()->name : 'Usuário' }}
                                     @if(auth()->check() && (auth()->user()->is_admin ?? false))
                                         <span class="text-yellow-500 ml-1">⭐</span>
                                     @endif
                                 </span>
                                 
                                 <!-- User Profession -->
                                 <div class="mt-1">
                                     <span class="text-xs text-gray-500 dark:text-gray-400">
                                         {{ auth()->check() ? (auth()->user()->profissao ?? 'Não informado') : 'Não informado' }}
                                     </span>
                                 </div>
                             </div>
                         </a>
                     </div>


                     <!-- Financeiro Section -->
                    <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_financeiro') || 'true') }" 
                         x-init="$watch('open', value => localStorage.setItem('sidebar_financeiro', JSON.stringify(value)))">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider hover:text-blue-900 dark:hover:text-blue-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile"
                                x-transition:enter="transition ease-in-out duration-150"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in-out duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Financeiro
                            </span>
                            <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                         
                         <div class="mt-2 space-y-1" :class="{ 'mt-0': $store.sidebar.collapsed }" 
                              x-show="open" 
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0 transform scale-95"
                              x-transition:enter-end="opacity-100 transform scale-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100 transform scale-100"
                              x-transition:leave-end="opacity-0 transform scale-95">
                             <a href="{{ route('financial.dashboard') }}" 
                                class="{{ request()->routeIs('financial.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('financial.dashboard') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Dashboard</span>
                             </a>

                             <a href="{{ route('financial.banks.index') }}" 
                                class="{{ request()->routeIs('financial.banks.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('financial.banks.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Bancos</span>
                             </a>

                             <a href="{{ route('financial.credit-cards.index') }}" 
                                class="{{ request()->routeIs('financial.credit-cards.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('financial.credit-cards.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Cartões</span>
                             </a>

                             <a href="{{ route('financial.categories.index') }}" 
                                class="{{ request()->routeIs('financial.categories.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                  <svg class="{{ request()->routeIs('financial.categories.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                  </svg>
                                  <span x-show="!$store.sidebar.collapsed" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Categorias</span>
                             </a>

                             <a href="{{ route('financial.transactions.index') }}" 
                                class="{{ request()->routeIs('financial.transactions.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                  <svg class="{{ request()->routeIs('financial.transactions.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                  </svg>
                                  <span x-show="!$store.sidebar.collapsed" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Transações</span>
                             </a>
                         </div>
                     </div>

                     <!-- Budget Section -->
                     <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_orcamentos') || 'true') }" 
                          x-init="$watch('open', value => localStorage.setItem('sidebar_orcamentos', JSON.stringify(value)))">
                         <button @click="open = !open" 
                                 class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-green-700 dark:text-green-300 uppercase tracking-wider hover:text-green-900 dark:hover:text-green-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                 x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile"
                                 x-transition:enter="transition ease-in-out duration-150"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in-out duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95">
                             <span class="flex items-center">
                                 <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                 </svg>
                                 Orçamentos
                             </span>
                             <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                             </svg>
                         </button>
                         
                         <div class="mt-2 space-y-1" :class="{ 'mt-0': $store.sidebar.collapsed }" 
                              x-show="open" 
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0 transform scale-95"
                              x-transition:enter-end="opacity-100 transform scale-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100 transform scale-100"
                              x-transition:leave-end="opacity-0 transform scale-95">
                             <a href="{{ route('orcamentos.dashboard') }}" 
                                class="{{ (request()->routeIs('dashboard') || request()->routeIs('orcamentos.dashboard')) ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ (request()->routeIs('dashboard') || request()->routeIs('orcamentos.dashboard')) ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Dashboard</span>
                             </a>

                             <a href="{{ route('orcamentos.index') }}" 
                                class="{{ request()->routeIs('orcamentos.index') || request()->routeIs('orcamentos.create') || request()->routeIs('orcamentos.edit') || request()->routeIs('orcamentos.show') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('orcamentos.index') || request()->routeIs('orcamentos.create') || request()->routeIs('orcamentos.edit') || request()->routeIs('orcamentos.show') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Orçamentos</span>
                             </a>

                             <a href="{{ route('clientes.index') }}" 
                                class="{{ request()->routeIs('clientes.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('clientes.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 00-5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Clientes</span>
                             </a>

                             <a href="{{ route('autores.index') }}" 
                                class="{{ request()->routeIs('autores.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('autores.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Autores</span>
                             </a>

                             <a href="{{ route('pagamentos.index') }}" 
                                class="{{ request()->routeIs('pagamentos.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('pagamentos.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Pagamentos</span>
                             </a>

                             <a href="{{ route('modelos-propostas.index') }}" 
                                class="{{ request()->routeIs('modelos-propostas.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('modelos-propostas.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Modelos</span>
                             </a>

                             <a href="{{ route('kanban.index') }}" 
                                class="{{ request()->routeIs('kanban.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('kanban.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Kanban</span>
                             </a>
                         </div>
                     </div>

                     <!-- Portfolio Section -->
                     <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_portfolio') || 'true') }" 
                          x-init="$watch('open', value => localStorage.setItem('sidebar_portfolio', JSON.stringify(value)))">
                         <!-- Collapsed state: show individual icons -->
                         <div x-show="$store.sidebar.collapsed && !$store.sidebar.isMobile" class="space-y-2">
                             <a href="{{ route('portfolio.dashboard') }}" 
                                class="{{ request()->routeIs('portfolio.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Dashboard">
                                 <svg class="{{ request()->routeIs('portfolio.dashboard') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                 </svg>
                             </a>
                             <a href="{{ route('portfolio.pipeline') }}" 
                                class="{{ request()->routeIs('portfolio.pipeline') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Pipeline">
                                 <svg class="{{ request()->routeIs('portfolio.pipeline') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                 </svg>
                             </a>
                             <a href="{{ route('portfolio.categories.index') }}" 
                                class="{{ request()->routeIs('portfolio.categories.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Categorias">
                                 <svg class="{{ request()->routeIs('portfolio.categories.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                 </svg>
                             </a>
                             <a href="{{ route('portfolio.works.index') }}" 
                                class="{{ request()->routeIs('portfolio.works.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Trabalhos">
                                 <svg class="{{ request()->routeIs('portfolio.works.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                 </svg>
                             </a>
                         </div>
                         
                         <!-- Expanded state: show normal module structure -->
                         <div x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile">
                             <button @click="open = !open" 
                                     class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wider hover:text-purple-900 dark:hover:text-purple-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                     x-transition:enter="transition ease-in-out duration-150"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in-out duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95">
                                 <span class="flex items-center">
                                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                     </svg>
                                     Portfólio
                                 </span>
                                 <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                 </svg>
                             </button>
                             
                             <div class="mt-2 space-y-1" 
                                  x-show="open" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0 transform scale-95"
                                  x-transition:enter-end="opacity-100 transform scale-100"
                                  x-transition:leave="transition ease-in duration-150"
                                  x-transition:leave-start="opacity-100 transform scale-100"
                                  x-transition:leave-end="opacity-0 transform scale-95">
                                 <a href="{{ route('portfolio.dashboard') }}" 
                                    class="{{ request()->routeIs('portfolio.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <svg class="{{ request()->routeIs('portfolio.dashboard') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                     </svg>
                                     <span class="ml-3">Dashboard</span>
                                 </a>
                                 <a href="{{ route('portfolio.pipeline') }}" 
                                    class="{{ request()->routeIs('portfolio.pipeline') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <svg class="{{ request()->routeIs('portfolio.pipeline') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                     </svg>
                                     <span class="ml-3">Pipeline</span>
                                 </a>
                                 <a href="{{ route('portfolio.categories.index') }}" 
                                    class="{{ request()->routeIs('portfolio.categories.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <svg class="{{ request()->routeIs('portfolio.categories.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                     </svg>
                                     <span class="ml-3">Categorias</span>
                                 </a>
                                 <a href="{{ route('portfolio.works.index') }}" 
                                    class="{{ request()->routeIs('portfolio.works.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <svg class="{{ request()->routeIs('portfolio.works.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                     </svg>
                                     <span class="ml-3">Trabalhos</span>
                                 </a>
                             </div>
                         </div>
                     </div>

                     <!-- Redes Sociais Section -->
                     <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_social') || 'true') }" 
                          x-init="$watch('open', value => localStorage.setItem('sidebar_social', JSON.stringify(value)))">
                         <!-- Collapsed state: show individual icons -->
                         <div x-show="$store.sidebar.collapsed && !$store.sidebar.isMobile" class="space-y-2">
                             <a href="{{ route('social-posts.calendar') }}" 
                                class="{{ request()->routeIs('social-posts.calendar') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Calendário">
                                 <i class="fas fa-calendar {{ request()->routeIs('social-posts.calendar') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                             <a href="{{ route('social-posts.index') }}" 
                                class="{{ request()->routeIs('social-posts.index', 'social-posts.show', 'social-posts.create', 'social-posts.edit') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Posts">
                                 <i class="fas fa-list {{ request()->routeIs('social-posts.index', 'social-posts.show', 'social-posts.create', 'social-posts.edit') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                         </div>
                         
                         <!-- Expanded state: show normal module structure -->
                         <div x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile">
                             <button @click="open = !open" 
                                     class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-red-700 dark:text-red-300 uppercase tracking-wider hover:text-red-900 dark:hover:text-red-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                     x-transition:enter="transition ease-in-out duration-150"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in-out duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95">
                                 <span class="flex items-center">
                                     <i class="fas fa-share-alt w-5 h-5 mr-2"></i>
                                     Redes Sociais
                                 </span>
                                 <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                 </svg>
                             </button>
                             
                             <div class="mt-2 space-y-1" 
                                  x-show="open" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0 transform scale-95"
                                  x-transition:enter-end="opacity-100 transform scale-100"
                                  x-transition:leave="transition ease-in duration-150"
                                  x-transition:leave-start="opacity-100 transform scale-100"
                                  x-transition:leave-end="opacity-0 transform scale-95">
                                 <a href="{{ route('social-posts.calendar') }}" 
                                    class="{{ request()->routeIs('social-posts.calendar') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <i class="fas fa-calendar {{ request()->routeIs('social-posts.calendar') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                     <span class="ml-3">Calendário</span>
                                 </a>
                                 <a href="{{ route('social-posts.index') }}" 
                                    class="{{ request()->routeIs('social-posts.index', 'social-posts.show', 'social-posts.create', 'social-posts.edit') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <i class="fas fa-list {{ request()->routeIs('social-posts.index', 'social-posts.show', 'social-posts.create', 'social-posts.edit') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                     <span class="ml-3">Posts</span>
                                 </a>
                             </div>
                         </div>
                     </div>

                     <!-- File Management Section -->
                     <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_files') || 'true') }" 
                          x-init="$watch('open', value => localStorage.setItem('sidebar_files', JSON.stringify(value)))">
                         <!-- Collapsed state: show individual icons -->
                         <div x-show="$store.sidebar.collapsed && !$store.sidebar.isMobile" class="space-y-2">
                             <a href="{{ route('files.index') }}" 
                                class="{{ request()->routeIs('files.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Meus Arquivos">
                                 <i class="fas fa-folder-open {{ request()->routeIs('files.index') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                             <a href="{{ route('files.create') }}" 
                                class="{{ request()->routeIs('files.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Upload">
                                 <i class="fas fa-cloud-upload-alt {{ request()->routeIs('files.create') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                             <a href="{{ route('file-categories.index') }}" 
                                class="{{ request()->routeIs('file-categories.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Categorias">
                                 <i class="fas fa-tags {{ request()->routeIs('file-categories.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                         </div>
                         
                         <!-- Expanded state: show normal module structure -->
                         <div x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile">
                             <button @click="open = !open" 
                                     class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wider hover:text-purple-900 dark:hover:text-purple-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                     x-transition:enter="transition ease-in-out duration-150"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in-out duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95">
                                 <span class="flex items-center">
                                     <i class="fas fa-folder w-5 h-5 mr-2"></i>
                                     Arquivos
                                 </span>
                                 <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                 </svg>
                             </button>
                             
                             <div class="mt-2 space-y-1" 
                                  x-show="open" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0 transform scale-95"
                                  x-transition:enter-end="opacity-100 transform scale-100"
                                  x-transition:leave="transition ease-in duration-150"
                                  x-transition:leave-start="opacity-100 transform scale-100"
                                  x-transition:leave-end="opacity-0 transform scale-95">
                                 <a href="{{ route('files.index') }}" 
                                    class="{{ request()->routeIs('files.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <i class="fas fa-folder-open {{ request()->routeIs('files.index') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                     <span class="ml-3">Meus Arquivos</span>
                                 </a>
                                 <a href="{{ route('files.create') }}" 
                                    class="{{ request()->routeIs('files.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <i class="fas fa-cloud-upload-alt {{ request()->routeIs('files.create') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                     <span class="ml-3">Upload</span>
                                 </a>
                                 <a href="{{ route('file-categories.index') }}" 
                                    class="{{ request()->routeIs('file-categories.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                     <i class="fas fa-tags {{ request()->routeIs('file-categories.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                     <span class="ml-3">Categorias</span>
                                 </a>
                             </div>
                         </div>
                     </div>

                     <!-- Asset Library Section -->
                     <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_assets') || 'true') }" 
                          x-init="$watch('open', value => localStorage.setItem('sidebar_assets', JSON.stringify(value)))">
                         <!-- Botão do módulo quando não colapsado -->
                         <button @click="open = !open" 
                                 class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wider hover:text-purple-900 dark:hover:text-purple-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                 x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile"
                                 x-transition:enter="transition ease-in-out duration-150"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in-out duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95">
                             <span class="flex items-center">
                                 <i class="fas fa-images w-5 h-5 mr-2"></i>
                                 Asset Library
                             </span>
                             <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                             </svg>
                         </button>
                         
                         <!-- Links individuais quando colapsado -->
                         <div class="space-y-1" x-show="$store.sidebar.collapsed && !$store.sidebar.isMobile">
                             <a href="{{ route('assets.index') }}" 
                                class="{{ request()->routeIs('assets.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Dashboard">
                                 <i class="fas fa-images {{ request()->routeIs('assets.index') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>

                             <a href="{{ route('assets.images') }}" 
                                class="{{ request()->routeIs('assets.images') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Imagens">
                                 <i class="fas fa-image {{ request()->routeIs('assets.images') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>

                             <a href="{{ route('assets.fonts') }}" 
                                class="{{ request()->routeIs('assets.fonts') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Fontes">
                                 <i class="fas fa-font {{ request()->routeIs('assets.fonts') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>

                             <a href="{{ route('assets.upload') }}" 
                                class="{{ request()->routeIs('assets.upload') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Upload">
                                 <i class="fas fa-upload {{ request()->routeIs('assets.upload') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                         </div>
                         
                         <!-- Links expandidos quando não colapsado -->
                         <div class="mt-2 space-y-1" :class="{ 'mt-0': $store.sidebar.collapsed }" 
                              x-show="open && (!$store.sidebar.collapsed || $store.sidebar.isMobile)" 
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0 transform scale-95"
                              x-transition:enter-end="opacity-100 transform scale-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100 transform scale-100"
                              x-transition:leave-end="opacity-0 transform scale-95">
                             <a href="{{ route('assets.index') }}" 
                                class="{{ request()->routeIs('assets.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-images {{ request()->routeIs('assets.index') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Dashboard</span>
                             </a>

                             <a href="{{ route('assets.images') }}" 
                                class="{{ request()->routeIs('assets.images') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-image {{ request()->routeIs('assets.images') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Imagens</span>
                             </a>

                             <a href="{{ route('assets.fonts') }}" 
                                class="{{ request()->routeIs('assets.fonts') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-font {{ request()->routeIs('assets.fonts') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Fontes</span>
                             </a>

                             <a href="{{ route('assets.upload') }}" 
                                class="{{ request()->routeIs('assets.upload') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-upload {{ request()->routeIs('assets.upload') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Upload</span>
                             </a>

                         </div>
                     </div>

                     <!-- Notifications Section -->
                     <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_notificacoes') || 'true') }" 
                          x-init="$watch('open', value => localStorage.setItem('sidebar_notificacoes', JSON.stringify(value)))">
                         <!-- Botão do módulo quando não colapsado -->
                         <button @click="open = !open" 
                                 class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wider hover:text-purple-900 dark:hover:text-purple-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                 x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile"
                                 x-transition:enter="transition ease-in-out duration-150"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in-out duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95">
                             <span class="flex items-center">
                                 <i class="fas fa-bell w-5 h-5 mr-2"></i>
                                 Notificações
                             </span>
                             <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                             </svg>
                         </button>
                         
                         <!-- Links individuais quando colapsado -->
                         <div class="space-y-1" x-show="$store.sidebar.collapsed && !$store.sidebar.isMobile">
                             <a href="{{ route('notifications.index') }}" 
                                class="{{ request()->routeIs('notifications.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors relative"
                                title="Dashboard">
                                 <i class="fas fa-bell {{ request()->routeIs('notifications.index') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <!-- Unread count badge -->
                                 <span id="unread-count-collapsed" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden"></span>
                             </a>

                             <a href="{{ route('notifications.preferences') }}" 
                                class="{{ request()->routeIs('notifications.preferences') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Preferências">
                                 <i class="fas fa-cog {{ request()->routeIs('notifications.preferences') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>

                             <a href="{{ route('notifications.logs.index') }}" 
                                class="{{ request()->routeIs('notifications.logs.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Logs">
                                 <i class="fas fa-list-alt {{ request()->routeIs('notifications.logs.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                         </div>
                         
                         <!-- Links expandidos quando não colapsado -->
                         <div class="mt-2 space-y-1" :class="{ 'mt-0': $store.sidebar.collapsed }" 
                              x-show="open && (!$store.sidebar.collapsed || $store.sidebar.isMobile)" 
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0 transform scale-95"
                              x-transition:enter-end="opacity-100 transform scale-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100 transform scale-100"
                              x-transition:leave-end="opacity-0 transform scale-95">
                             <a href="{{ route('notifications.index') }}" 
                                class="{{ request()->routeIs('notifications.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors relative"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-bell {{ request()->routeIs('notifications.index') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Dashboard</span>
                                 <!-- Unread count badge -->
                                 <span id="unread-count-expanded" class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden"></span>
                             </a>

                             <a href="{{ route('notifications.preferences') }}" 
                                class="{{ request()->routeIs('notifications.preferences') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-cog {{ request()->routeIs('notifications.preferences') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Preferências</span>
                             </a>

                             <a href="{{ route('notifications.logs.index') }}" 
                                class="{{ request()->routeIs('notifications.logs.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-list-alt {{ request()->routeIs('notifications.logs.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Logs</span>
                             </a>

                         </div>
                     </div>

                     <!-- Admin Section -->
                     <div class="mt-8" x-data="{ open: JSON.parse(localStorage.getItem('sidebar_administracao') || 'true') }" 
                          x-init="$watch('open', value => localStorage.setItem('sidebar_administracao', JSON.stringify(value)))">
                         <!-- Botão do módulo quando não colapsado -->
                         <button @click="open = !open" 
                                 class="w-full flex items-center justify-between px-3 py-3 text-left text-sm font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider hover:text-orange-900 dark:hover:text-orange-100 transition-colors bg-gray-50 dark:bg-gray-800 rounded-md"
                                 x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile"
                                 x-transition:enter="transition ease-in-out duration-150"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in-out duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95">
                             <span class="flex items-center">
                                 <i class="fas fa-cogs w-5 h-5 mr-2"></i>
                                 Administração
                             </span>
                             <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                             </svg>
                         </button>
                         
                         <!-- Links individuais quando colapsado -->
                         <div class="space-y-1" x-show="$store.sidebar.collapsed && !$store.sidebar.isMobile">
                             @if(auth()->check() && auth()->user()->is_admin)
                             <a href="{{ route('users.index') }}" 
                                class="{{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Usuários">
                                 <i class="fas fa-users {{ request()->routeIs('users.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>

                             <a href="{{ route('admin.user-approvals.index') }}" 
                                class="{{ request()->routeIs('admin.user-approvals.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Aprovação de Usuários">
                                 <i class="fas fa-user-check {{ request()->routeIs('admin.user-approvals.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>

                             <a href="{{ route('admin.temp-file-settings.index') }}" 
                                class="{{ request()->routeIs('admin.temp-file-settings.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Arquivos Temporários">
                                 <i class="fas fa-clock {{ request()->routeIs('admin.temp-file-settings.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                             </a>
                             @endif

                             <a href="{{ route('profile.show') }}" 
                                class="{{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Perfil">
                                 <svg class="{{ request()->routeIs('profile.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                 </svg>
                             </a>

                             <a href="{{ route('settings.index') }}" 
                                class="{{ request()->routeIs('settings.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                title="Configurações">
                                 <svg class="{{ request()->routeIs('settings.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                 </svg>
                             </a>
                         </div>
                         
                         <!-- Links expandidos quando não colapsado -->
                         <div class="mt-2 space-y-1" :class="{ 'mt-0': $store.sidebar.collapsed }" 
                              x-show="open && (!$store.sidebar.collapsed || $store.sidebar.isMobile)" 
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0 transform scale-95"
                              x-transition:enter-end="opacity-100 transform scale-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100 transform scale-100"
                              x-transition:leave-end="opacity-0 transform scale-95">
                             @if(auth()->check() && auth()->user()->is_admin)
                             <a href="{{ route('users.index') }}" 
                                class="{{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-users {{ request()->routeIs('users.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Usuários</span>
                             </a>

                             <a href="{{ route('admin.user-approvals.index') }}" 
                                class="{{ request()->routeIs('admin.user-approvals.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-user-check {{ request()->routeIs('admin.user-approvals.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Aprovação de Usuários</span>
                             </a>

                             <a href="{{ route('admin.temp-file-settings.index') }}" 
                                class="{{ request()->routeIs('admin.temp-file-settings.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <i class="fas fa-clock {{ request()->routeIs('admin.temp-file-settings.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6"></i>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Arquivos Temporários</span>
                             </a>
                             @endif

                             <a href="{{ route('profile.show') }}" 
                                class="{{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('profile.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Perfil</span>
                             </a>

                             <a href="{{ route('settings.index') }}" 
                                class="{{ request()->routeIs('settings.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="{{ request()->routeIs('settings.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Configurações</span>
                             </a>

                         </div>
                     </div>

                     <!-- Logout Button - Outside Administration Module -->
                     <div class="mt-8">
                         <form method="POST" action="{{ route('logout') }}">
                             @csrf
                             <button type="submit" 
                                     class="w-full text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:text-red-300 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                     :class="{ 'justify-center': $store.sidebar.collapsed }">
                                 <svg class="text-red-400 group-hover:text-red-500 dark:group-hover:text-red-300 flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                 </svg>
                                 <span x-show="!$store.sidebar.collapsed || $store.sidebar.isMobile" 
                                       x-transition:enter="transition ease-in-out duration-150"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in-out duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="ml-3">Sair</span>
                             </button>
                         </form>
                     </div>


                 </nav>
             </div>
         </div>



         <!-- Theme Toggle Button -->
         <div class="fixed top-4 right-4 z-50 hidden lg:block">
             <button id="theme-toggle" 
                     onclick="toggleTheme()"
                     class="p-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                 <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                 </svg>
                 <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M10 2L13.09 8.26L20 9L14 14.74L15.18 21.02L10 17.77L4.82 21.02L6 14.74L0 9L6.91 8.26L10 2Z"></path>
                 </svg>
             </button>
         </div>





        <!-- Main Content Area -->
        <div class="flex-1"
             :class="{
                 'lg:ml-64': !$store.sidebar.collapsed && !$store.sidebar.isMobile,
                 'lg:ml-16': $store.sidebar.collapsed && !$store.sidebar.isMobile,
                 'ml-0': $store.sidebar.isMobile
             }">
            <!-- Page Content -->
            <main class="py-6 px-4 sm:px-6 lg:px-8 pt-20 lg:pt-6 w-full max-w-full overflow-x-hidden">
                <div class="w-full max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Alpine.js is loaded via Vite in app.js -->
    
    <!-- Theme initialization -->
    <script>
        // Initialize theme based on localStorage or system preference
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Update theme toggle icon
        function updateThemeIcon() {
            // Desktop icons
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            
            // Mobile icons
            const themeToggleDarkIconMobile = document.getElementById('theme-toggle-dark-icon-mobile');
            const themeToggleLightIconMobile = document.getElementById('theme-toggle-light-icon-mobile');
            
            if (document.documentElement.classList.contains('dark')) {
                // Show light icons (to switch to light mode)
                if (themeToggleLightIcon) {
                    themeToggleLightIcon.classList.remove('hidden');
                }
                if (themeToggleDarkIcon) {
                    themeToggleDarkIcon.classList.add('hidden');
                }
                if (themeToggleLightIconMobile) {
                    themeToggleLightIconMobile.classList.remove('hidden');
                }
                if (themeToggleDarkIconMobile) {
                    themeToggleDarkIconMobile.classList.add('hidden');
                }
            } else {
                // Show dark icons (to switch to dark mode)
                if (themeToggleDarkIcon) {
                    themeToggleDarkIcon.classList.remove('hidden');
                }
                if (themeToggleLightIcon) {
                    themeToggleLightIcon.classList.add('hidden');
                }
                if (themeToggleDarkIconMobile) {
                    themeToggleDarkIconMobile.classList.remove('hidden');
                }
                if (themeToggleLightIconMobile) {
                    themeToggleLightIconMobile.classList.add('hidden');
                }
            }
        }
        
        // Toggle theme function
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcon();
        }
        
        // Initialize icon when page loads
        document.addEventListener('DOMContentLoaded', updateThemeIcon);
    </script>
    
    <!-- Loading Screen Script -->
    <script>
        // Show loading screen immediately
        document.addEventListener('DOMContentLoaded', function() {
            const loadingScreen = document.getElementById('loading-screen');
            
            // Hide loading screen when page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    loadingScreen.style.opacity = '0';
                    setTimeout(function() {
                        loadingScreen.style.display = 'none';
                    }, 500);
                }, 300); // Small delay to ensure smooth transition
            });
        });
        
        // Show loading screen on page navigation (for SPA-like behavior)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.target && !link.download && 
                link.href.indexOf(window.location.origin) === 0 && 
                !link.href.includes('#') && 
                !link.classList.contains('no-loading')) {
                
                const loadingScreen = document.getElementById('loading-screen');
                loadingScreen.style.display = 'flex';
                loadingScreen.style.opacity = '1';
            }
        });
        
        // Handle form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form.classList.contains('no-loading')) {
                const loadingScreen = document.getElementById('loading-screen');
                loadingScreen.style.display = 'flex';
                loadingScreen.style.opacity = '1';
            }
        });
    </script>
    
    <!-- Notification Count Script -->
    <script>
        // Global variable to track if a request is in progress
        let notificationRequestInProgress = false;
        let notificationAbortController = null;

        // Function to update unread notification count with improved error handling
        async function updateUnreadCount() {
            // Check if user is authenticated first
            @guest
                // User is not authenticated, hide badges and return
                hideNotificationBadges();
                return;
            @endguest

            // Only proceed if user is authenticated
            @auth
            // Prevent multiple simultaneous requests
            if (notificationRequestInProgress) {
                console.log('Notification count request already in progress, skipping');
                return;
            }

            try {
                notificationRequestInProgress = true;

                // Check if CSRF token exists
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.warn('CSRF token not found, skipping notification count update');
                    return;
                }

                // Create abort controller for this request
                notificationAbortController = new AbortController();

                const response = await fetch('{{ route("notifications.api.unread-count") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin', // Include cookies for authentication
                    cache: 'no-cache',
                    signal: notificationAbortController.signal // Add abort signal
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && typeof data.count !== 'undefined') {
                        updateNotificationBadges(data.count);
                    } else {
                        console.warn('Invalid response format from notification API:', data);
                        hideNotificationBadges();
                    }
                } else if (response.status === 401) {
                    // User session expired, hide badges
                    console.warn('User session expired, hiding notification badges');
                    hideNotificationBadges();
                } else if (response.status === 403) {
                    // Forbidden, likely CSRF token issue
                    console.warn('CSRF token validation failed, hiding notification badges');
                    hideNotificationBadges();
                } else {
                    // Other HTTP errors
                    console.warn('HTTP error while fetching notification count:', response.status, response.statusText);
                    hideNotificationBadges();
                }
            } catch (error) {
                if (error.name === 'AbortError') {
                    console.log('Notification count request was cancelled');
                } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    console.warn('Network error while fetching notification count:', error.message);
                    hideNotificationBadges();
                } else {
                    console.error('Error in updateUnreadCount function:', error);
                    hideNotificationBadges();
                }
            } finally {
                notificationRequestInProgress = false;
                notificationAbortController = null;
            }
            @endauth
        }
        
        // Helper function to update notification badges
        function updateNotificationBadges(count) {
            const collapsedBadge = document.getElementById('unread-count-collapsed');
            const expandedBadge = document.getElementById('unread-count-expanded');
            
            if (count > 0) {
                const displayCount = count > 99 ? '99+' : count.toString();
                if (collapsedBadge) {
                    collapsedBadge.textContent = displayCount;
                    collapsedBadge.classList.remove('hidden');
                }
                if (expandedBadge) {
                    expandedBadge.textContent = displayCount;
                    expandedBadge.classList.remove('hidden');
                }
            } else {
                hideNotificationBadges();
            }
        }
        
        // Helper function to hide notification badges
        function hideNotificationBadges() {
            const collapsedBadge = document.getElementById('unread-count-collapsed');
            const expandedBadge = document.getElementById('unread-count-expanded');
            
            if (collapsedBadge) {
                collapsedBadge.classList.add('hidden');
            }
            if (expandedBadge) {
                expandedBadge.classList.add('hidden');
            }
        }
        
        // Update count on page load (only if authenticated)
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            // Add a small delay to ensure the page is fully loaded
            setTimeout(updateUnreadCount, 500);
        });
        
        // Update count every 30 seconds (only if authenticated)
        let notificationInterval = setInterval(updateUnreadCount, 30000);
        
        // Update count when returning to the page (only if authenticated)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Cancel any ongoing request first
                if (notificationAbortController) {
                    notificationAbortController.abort();
                }
                
                // Clear existing interval and create a new one to reset timing
                clearInterval(notificationInterval);
                
                // Wait a bit before making the request to avoid conflicts
                setTimeout(() => {
                    updateUnreadCount();
                    notificationInterval = setInterval(updateUnreadCount, 30000);
                }, 100);
            }
        });
        
        // Clean up interval and cancel requests when page is unloaded
        window.addEventListener('beforeunload', function() {
            if (notificationInterval) {
                clearInterval(notificationInterval);
            }
            if (notificationAbortController) {
                notificationAbortController.abort();
            }
        });
        @endauth
    </script>
    
    @stack('scripts')
</body>
</html>