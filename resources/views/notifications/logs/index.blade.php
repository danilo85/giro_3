@extends('layouts.app')

@section('title', 'Logs de Notificações')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('notifications.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 4.828A4 4 0 015.5 4H9v1H5.5a3 3 0 00-2.121.879l-.707.707A1 1 0 002 7.414V11H1V7.414a2 2 0 01.586-1.414l.707-.707a5 5 0 013.535-1.465z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Notificações
        </a>
        <a href="{{ route('notifications.preferences') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Preferências
        </a>
        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white dark:bg-blue-700 dark:text-blue-200">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Logs
        </span>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Logs de Notificações</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Histórico de envio e entrega das suas notificações</p>
            </div>
            <a href="{{ route('notifications.index') }}" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700" title="Voltar para Notificações">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-white text-opacity-90">Total</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-white text-opacity-90">Entregues</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['delivered'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-paper-plane text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-white text-opacity-90">Enviados</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['sent'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-white text-opacity-90">Falharam</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['failed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Filtros</h3>
            
            <!-- Search Bar -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Buscar logs..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="mb-4">
                <div class="flex flex-wrap gap-2">
                    <!-- Status Filters -->
                    @if(count($statuses) > 0)
                        @foreach($statuses as $statusValue => $statusLabel)
                            <button type="button" 
                                    onclick="quickFilter('status', '{{ $statusValue }}')"
                                    class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ request('status') == $statusValue ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                {{ $statusLabel }}
                            </button>
                        @endforeach
                    @endif

                    <!-- Channel Filters -->
                    @if(count($channels) > 0)
                        @foreach($channels as $channelValue => $channelLabel)
                            <button type="button" 
                                    onclick="quickFilter('channel', '{{ $channelValue }}')"
                                    class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ request('channel') == $channelValue ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                {{ $channelLabel }}
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Advanced Filters -->
            <form method="GET" action="{{ route('notifications.logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os status</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Channel Filter -->
                <div>
                    <label for="channel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Canal</label>
                    <select name="channel" id="channel" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os canais</option>
                        @foreach($channels as $value => $label)
                            <option value="{{ $value }}" {{ request('channel') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Notification Filter -->
                <div>
                    <label for="notification_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notificação</label>
                    <select name="notification_id" id="notification_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas as notificações</option>
                        @foreach($notifications as $notification)
                            <option value="{{ $notification->id }}" {{ request('notification_id') == $notification->id ? 'selected' : '' }}>
                                {{ Str::limit($notification->title, 50) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>

            <!-- Clear Filters Button -->
            @if(request()->hasAny(['status', 'channel', 'notification_id', 'search']))
                <div class="mt-4">
                    <button onclick="clearFilters()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">
                        <i class="fas fa-times mr-2"></i>
                        Limpar Filtros
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Logs de Notificações</h3>
        </div>

        @if($logs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($logs as $log)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden {{ $log->status === 'failed' ? 'ring-2 ring-red-500 ring-opacity-20' : '' }}">
                        <!-- Header do Card -->
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $log->notification->title }}
                                        </h4>
                                        <span class="notification-type-badge px-2 py-1 text-xs font-medium rounded-full {{ $log->notification->getTypeBadgeClass() }}">
                                            {{ $log->notification->getTypeLabel() }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4 mb-3">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($log->channel === 'email') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @elseif($log->channel === 'sms') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                            @endif">
                                            {{ $log->getChannelLabel() }}
                                        </span>
                                        
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($log->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($log->status === 'sent') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                            @elseif($log->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                            @endif">
                                            {{ $log->getStatusLabel() }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Enviado:</strong> {{ $log->sent_at ? $log->sent_at->format('d/m/Y H:i:s') : '-' }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Entregue:</strong> {{ $log->delivered_at ? $log->delivered_at->format('d/m/Y H:i:s') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Flex grow para empurrar footer para baixo -->
                        <div class="flex-grow"></div>
                        
                        <!-- Footer do Card com Botões de Ação -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600 mt-auto">
                            <div class="flex justify-center items-center gap-1 w-full">
                                <!-- Visualizar -->
                                <a href="{{ route('notifications.logs.show', $log) }}" 
                                   class="p-2 rounded-lg text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                                   title="Visualizar Detalhes">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Tentar Novamente (apenas para logs falhados) -->
                                @if($log->isFailed())
                                    <button onclick="retryLog('{{ $log->id }}')" 
                                            class="p-2 rounded-lg text-green-500 hover:text-green-600 dark:hover:text-green-400 transition-colors hover:bg-green-50 dark:hover:bg-green-900/20" 
                                            title="Tentar Novamente">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum log encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400">Não há logs de notificações para exibir com os filtros aplicados.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal System -->
<div id="customModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center" style="z-index: 10003;">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 mr-3">
                    <div id="modalIcon" class="w-8 h-8 rounded-full flex items-center justify-center">
                        <!-- Icon will be inserted here -->
                    </div>
                </div>
                <div class="flex-1">
                    <h3 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-white"></h3>
                </div>
            </div>
            <div class="mb-6">
                <p id="modalMessage" class="text-sm text-gray-600 dark:text-gray-400"></p>
            </div>
            <div class="flex justify-end space-x-3" id="modalButtons">
                <!-- Buttons will be inserted here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Custom Modal System
class CustomModal {
    constructor() {
        this.modal = document.getElementById('customModal');
        this.modalContent = document.getElementById('modalContent');
        this.modalIcon = document.getElementById('modalIcon');
        this.modalTitle = document.getElementById('modalTitle');
        this.modalMessage = document.getElementById('modalMessage');
        this.modalButtons = document.getElementById('modalButtons');
        
        // Close modal when clicking outside
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.hide();
            }
        });
    }
    
    show(options) {
        const { type = 'info', title, message, buttons = [] } = options;
        
        // Set icon and colors based on type
        const iconConfig = {
            error: {
                icon: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
                bgColor: 'bg-red-500',
                textColor: 'text-red-600 dark:text-red-400'
            },
            warning: {
                icon: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
                bgColor: 'bg-yellow-500',
                textColor: 'text-yellow-600 dark:text-yellow-400'
            },
            success: {
                icon: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                bgColor: 'bg-green-500',
                textColor: 'text-green-600 dark:text-green-400'
            },
            info: {
                icon: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
                bgColor: 'bg-blue-500',
                textColor: 'text-blue-600 dark:text-blue-400'
            }
        };
        
        const config = iconConfig[type];
        
        // Set icon
        this.modalIcon.className = `w-8 h-8 rounded-full flex items-center justify-center ${config.bgColor}`;
        this.modalIcon.innerHTML = config.icon;
        
        // Set title and message
        this.modalTitle.textContent = title;
        this.modalTitle.className = `text-lg font-medium ${config.textColor}`;
        this.modalMessage.textContent = message;
        
        // Set buttons
        this.modalButtons.innerHTML = '';
        buttons.forEach(button => {
            const btn = document.createElement('button');
            btn.textContent = button.text;
            btn.className = button.className || 'px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 rounded-md transition-colors';
            btn.onclick = () => {
                if (button.action) button.action();
                this.hide();
            };
            this.modalButtons.appendChild(btn);
        });
        
        // Show modal with animation
        this.modal.classList.remove('hidden');
        this.modal.classList.add('flex');
        
        setTimeout(() => {
            this.modalContent.classList.remove('scale-95', 'opacity-0');
            this.modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    hide() {
        this.modalContent.classList.remove('scale-100', 'opacity-100');
        this.modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.modal.classList.remove('flex');
        }, 300);
    }
}

// Initialize modal system
const modal = new CustomModal();

// Convenience functions
function showAlert(message, title = 'Aviso') {
    modal.show({
        type: 'info',
        title: title,
        message: message,
        buttons: [
            { text: 'OK', className: 'px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors' }
        ]
    });
}

function showError(message, title = 'Erro') {
    modal.show({
        type: 'error',
        title: title,
        message: message,
        buttons: [
            { text: 'OK', className: 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors' }
        ]
    });
}

function showSuccess(message, title = 'Sucesso') {
    modal.show({
        type: 'success',
        title: title,
        message: message,
        buttons: [
            { text: 'OK', className: 'px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors' }
        ]
    });
}

function showConfirm(message, onConfirm, title = 'Confirmação') {
    modal.show({
        type: 'warning',
        title: title,
        message: message,
        buttons: [
            { text: 'Cancelar', className: 'px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 rounded-md transition-colors' },
            { text: 'Confirmar', className: 'px-4 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-md transition-colors', action: onConfirm }
        ]
    });
}

function retryLog(logId) {
    showConfirm('Tem certeza que deseja tentar enviar esta notificação novamente?', () => {
        fetch(`/notifications/logs/${logId}/retry`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Notificação reenviada com sucesso!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showError('Erro ao reenviar notificação: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Erro ao reenviar notificação.');
        });
    }, 'Reenviar Notificação');
}

function quickFilter(param, value) {
    const url = new URL(window.location);
    
    if (url.searchParams.get(param) === value) {
        // Se o filtro já está ativo, remove ele
        url.searchParams.delete(param);
    } else {
        // Caso contrário, aplica o filtro
        url.searchParams.set(param, value);
    }
    
    // Remove a página para voltar à primeira
    url.searchParams.delete('page');
    
    window.location.href = url.toString();
}

function clearFilters() {
    const url = new URL(window.location);
    url.searchParams.delete('status');
    url.searchParams.delete('channel');
    url.searchParams.delete('notification_id');
    url.searchParams.delete('search');
    url.searchParams.delete('page');
    
    window.location.href = url.toString();
}

// Real-time search
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        const url = new URL(window.location);
        if (e.target.value.trim()) {
            url.searchParams.set('search', e.target.value.trim());
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }, 500);
});
</script>
@endpush
@endsection