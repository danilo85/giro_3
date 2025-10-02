@extends('layouts.app')

@section('title', 'Notificações')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white dark:bg-blue-700 dark:text-blue-200">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 4.828A4 4 0 015.5 4H9v1H5.5a3 3 0 00-2.121.879l-.707.707A1 1 0 002 7.414V11H1V7.414a2 2 0 01.586-1.414l.707-.707a5 5 0 013.535-1.465z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Notificações
        </span>
        <a href="{{ route('notifications.preferences') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Preferências
        </a>
        <a href="{{ route('notifications.logs.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Logs
        </a>
    </div>

    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Notificações</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Gerencie suas notificações e alertas</p>
            </div>
            <div class="flex items-center">
                <button onclick="markAllAsRead()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors w-full sm:w-auto">
                    <i class="fas fa-check-double mr-1 sm:mr-2"></i>
                    <span class="hidden sm:inline">Marcar Todas como Lidas</span>
                    <span class="sm:hidden">Marcar Todas</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 4.828A4 4 0 015.5 4H9v1H5.5a3 3 0 00-2.121.879l-.707.707A1 1 0 002 7.414V11H1V7.414a2 2 0 01.586-1.414l.707-.707a5 5 0 013.535-1.465z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-100">Total</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-100">Não Lidas</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['unread'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-100">Lidas</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['read'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-amber-100">Hoje</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['today'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8">
        <!-- Busca -->
        <div class="mb-4 sm:mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       id="search" 
                       placeholder="Buscar notificações..." 
                       value="{{ request('search') }}"
                       class="block w-full pl-9 sm:pl-10 pr-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
            </div>
        </div>

        <!-- Filtros Rápidos -->
        <div class="mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-0 sm:mr-3">Filtros rápidos:</span>
                
                <div class="flex flex-wrap gap-2">
                    <!-- Status -->
                    <button onclick="quickFilter('status', '')" 
                            class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full transition-colors {{ request('status') == '' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        Todas
                    </button>
                    <button onclick="quickFilter('status', 'unread')" 
                            class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full transition-colors {{ request('status') == 'unread' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        Não Lidas
                    </button>
                    <button onclick="quickFilter('status', 'read')" 
                            class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full transition-colors {{ request('status') == 'read' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        Lidas
                    </button>
                </div>
            </div>
        </div>

        <!-- Tipos de Notificação -->
        @if(count($types) > 0)
        <div class="mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-0 sm:mr-3">Tipos:</span>
                
                <div class="flex flex-wrap gap-2">
                    <button onclick="quickFilter('type', '')" 
                            class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full transition-colors {{ request('type') == '' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        Todos
                    </button>
                    @foreach($types as $typeKey => $typeLabel)
                    <button onclick="quickFilter('type', '{{ $typeKey }}')" 
                            class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full transition-colors {{ request('type') == $typeKey ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        {{ $typeLabel }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Botão Limpar Filtros -->
        @if(request('search') || request('status') || request('type'))
        <div class="flex justify-center sm:justify-end">
            <button onclick="clearFilters()" 
                    class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Limpar filtros
            </button>
        </div>
        @endif
    </div>

    <!-- Lista de Notificações -->
    <div class="mb-8">
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Notificações</h3>
                
                <!-- Controles de Ação em Lote -->
                <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <!-- Selecionar Todas -->
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" 
                               class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <label for="selectAll" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Selecionar Todas</label>
                    </div>
                    
                    <!-- Ações em Lote -->
                    <div class="flex items-center space-x-2">
                        <select id="bulkAction" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs sm:text-sm flex-1 sm:flex-none">
                            <option value="">Ações em Lote</option>
                            <option value="mark_read">Marcar como Lidas</option>
                            <option value="mark_unread">Marcar como Não Lidas</option>
                            <option value="delete">Excluir</option>
                        </select>
                        <button onclick="executeBulkAction()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors whitespace-nowrap">
                            Aplicar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        @if($notifications->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($notifications as $notification)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col {{ $notification->read_at ? '' : 'ring-2 ring-blue-500 ring-opacity-20' }}">
                        <!-- Header do Card -->
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="notification_ids[]" value="{{ $notification->id }}" 
                                           class="notification-checkbox rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 flex-shrink-0">
                                    
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white" title="{{ $notification->title }}">{{ Str::limit($notification->title, 25) }}</h3>
                                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ $notification->created_at->format('d/m/Y') }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if(!$notification->read_at)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Nova
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Badges Row -->
                            <div class="flex flex-wrap gap-1 mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $notification->getTypeBadgeClass() }}">
                                    {{ $notification->getTypeLabel() }}
                                </span>
                            </div>
                            
                            <!-- Message -->
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400 break-words" title="{{ $notification->message }}">
                                    {{ Str::limit($notification->message, 80) }}
                                </p>
                            </div>
                        </div>

                        <!-- Flex grow para empurrar os botões para o rodapé -->
                        <div class="flex-grow"></div>
                        
                        <!-- Actions Footer -->
                        <div class="flex items-center justify-center px-2 py-3 border-t border-gray-200 dark:border-gray-700 mt-auto">
                            <div class="flex justify-center items-center gap-1 w-full">
                                <!-- Detalhes -->
                                <a href="{{ route('notifications.show', $notification) }}" 
                                   class="p-2 rounded-lg text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                                   title="Ver Detalhes">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Marcar como Lida/Não Lida -->
                                @if(!$notification->read_at)
                                    <button onclick="markAsRead('{{ $notification->id }}')" 
                                            class="p-2 rounded-lg text-green-500 hover:text-green-600 dark:hover:text-green-400 transition-colors hover:bg-green-50 dark:hover:bg-green-900/20" 
                                            title="Marcar como Lida">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button onclick="markAsUnread('{{ $notification->id }}')" 
                                            class="p-2 rounded-lg text-yellow-500 hover:text-yellow-600 dark:hover:text-yellow-400 transition-colors hover:bg-yellow-50 dark:hover:bg-yellow-900/20" 
                                            title="Marcar como Não Lida">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </button>
                                @endif
                                
                                <!-- Excluir -->
                                <button onclick="deleteNotification('{{ $notification->id }}')" 
                                        class="p-2 rounded-lg text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20" 
                                        title="Excluir">
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 4.828A4 4 0 015.5 4H9v1H5.5a3 3 0 00-2.121.879l-.707.707A1 1 0 002 7.414V11H1V7.414a2 2 0 01.586-1.414l.707-.707a5 5 0 013.535-1.465z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma notificação encontrada</h3>
                    <p class="text-gray-600 dark:text-gray-400">Você não possui notificações no momento.</p>
                </div>
            </div>
        @endif
        
        @if($notifications->hasPages())
            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Mostrando {{ $notifications->firstItem() }} a {{ $notifications->lastItem() }} de {{ $notifications->total() }} notificações
                        </div>
                        <div>
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let selectedNotifications = [];

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    if (selectAllCheckbox.checked) {
        // Select all
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        selectedNotifications = Array.from(checkboxes).map(cb => cb.value);
    } else {
        // Deselect all
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectedNotifications = [];
    }
    
    updateBulkActionVisibility();
}

function updateBulkActionVisibility() {
    // Esta função pode ser expandida no futuro se houver elementos de ações em lote
    // Por enquanto, apenas atualiza o estado do checkbox "Selecionar Todas"
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    
    if (selectedNotifications.length === checkboxes.length && checkboxes.length > 0) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else if (selectedNotifications.length > 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('notification-checkbox')) {
        const notificationId = e.target.value;
        
        if (e.target.checked) {
            if (!selectedNotifications.includes(notificationId)) {
                selectedNotifications.push(notificationId);
            }
        } else {
            selectedNotifications = selectedNotifications.filter(id => id !== notificationId);
        }
        
        updateBulkActionVisibility();
    }
});

function markAsRead(notificationId) {
    const xhr = new XMLHttpRequest();
    xhr.open('PATCH', `/notifications/${notificationId}/read`, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        location.reload();
                    } else {
                        showError('Erro ao marcar notificação como lida');
                    }
                } catch (e) {
                    showError('Erro ao marcar notificação como lida');
                }
            } else {
                showError('Erro ao marcar notificação como lida');
            }
        }
    };
    
    xhr.send();
}

function markAsUnread(notificationId) {
    const xhr = new XMLHttpRequest();
    xhr.open('PATCH', `/notifications/${notificationId}/unread`, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        location.reload();
                    } else {
                        showError('Erro ao marcar notificação como não lida');
                    }
                } catch (e) {
                    showError('Erro ao marcar notificação como não lida');
                }
            } else {
                showError('Erro ao marcar notificação como não lida');
            }
        }
    };
    
    xhr.send();
}

function deleteNotification(notificationId) {
    showConfirm('Tem certeza que deseja excluir esta notificação?', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('DELETE', `/notifications/${notificationId}`, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.setRequestHeader('Content-Type', 'application/json');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            location.reload();
                        } else {
                            showError('Erro ao excluir notificação');
                        }
                    } catch (e) {
                        showError('Erro ao excluir notificação');
                    }
                } else {
                    showError('Erro ao excluir notificação');
                }
            }
        };
        
        xhr.send();
    });
}

function markAllAsRead() {
    showConfirm('Tem certeza que deseja marcar todas as notificações como lidas?', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('PATCH', '/notifications/mark-all-read', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.setRequestHeader('Content-Type', 'application/json');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            location.reload();
                        } else {
                            showError('Erro ao marcar todas as notificações como lidas');
                        }
                    } catch (e) {
                        showError('Erro ao marcar todas as notificações como lidas');
                    }
                } else {
                    showError('Erro ao marcar todas as notificações como lidas');
                }
            }
        };
        
        xhr.send();
    });
}

function executeBulkAction() {
    const bulkActionSelect = document.getElementById('bulkAction');
    const action = bulkActionSelect.value;
    
    if (!action) {
        showAlert('Selecione uma ação para executar', 'warning', 'Atenção');
        return;
    }
    
    bulkAction(action);
}

function bulkAction(action) {
    if (selectedNotifications.length === 0) {
        showAlert('Selecione pelo menos uma notificação', 'warning', 'Atenção');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'mark_read':
            confirmMessage = 'Marcar as notificações selecionadas como lidas?';
            break;
        case 'mark_unread':
            confirmMessage = 'Marcar as notificações selecionadas como não lidas?';
            break;
        case 'delete':
            confirmMessage = 'Excluir as notificações selecionadas?';
            break;
    }
    
    showConfirm(confirmMessage, function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/notifications/bulk-action', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.setRequestHeader('Content-Type', 'application/json');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            location.reload();
                        } else {
                            showError('Erro ao executar ação em lote');
                        }
                    } catch (e) {
                        showError('Erro ao executar ação em lote');
                    }
                } else {
                    showError('Erro ao executar ação em lote');
                }
            }
        };
        
        xhr.send(JSON.stringify({
            action: action,
            notification_ids: selectedNotifications
        }));
    });
}

// Função para filtro rápido
function quickFilter(param, value) {
    const url = new URL(window.location);
    if (value === '') {
        url.searchParams.delete(param);
    } else {
        url.searchParams.set(param, value);
    }
    url.searchParams.delete('page'); // Reset pagination
    window.location.href = url.toString();
}

// Função para limpar filtros
function clearFilters() {
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('status');
    url.searchParams.delete('type');
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

// Busca em tempo real
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
        const url = new URL(window.location);
        if (this.value.trim() === '') {
            url.searchParams.delete('search');
        } else {
            url.searchParams.set('search', this.value.trim());
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }, 500);
});

// Sistema de Modal Personalizado
class CustomModal {
    constructor() {
        this.createModalHTML();
    }

    createModalHTML() {
        // Remove modal existente se houver
        const existingModal = document.getElementById('customModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Cria o HTML do modal
        const modalHTML = `
            <div id="customModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden" style="z-index: 10003;">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div id="modalIcon" class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                <!-- Ícone será inserido aqui -->
                            </div>
                            <h3 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-white">
                                <!-- Título será inserido aqui -->
                            </h3>
                        </div>
                        <div id="modalMessage" class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                            <!-- Mensagem será inserida aqui -->
                        </div>
                        <div id="modalButtons" class="flex justify-end space-x-3">
                            <!-- Botões serão inseridos aqui -->
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    show(options) {
        const modal = document.getElementById('customModal');
        const modalIcon = document.getElementById('modalIcon');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalButtons = document.getElementById('modalButtons');

        // Configurar ícone e cores baseado no tipo
        let iconHTML = '';
        let iconClass = '';
        
        switch (options.type) {
            case 'error':
                iconHTML = '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                iconClass = 'bg-red-100 dark:bg-red-900';
                break;
            case 'warning':
                iconHTML = '<svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
                iconClass = 'bg-yellow-100 dark:bg-yellow-900';
                break;
            case 'success':
                iconHTML = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                iconClass = 'bg-green-100 dark:bg-green-900';
                break;
            case 'info':
            default:
                iconHTML = '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                iconClass = 'bg-blue-100 dark:bg-blue-900';
                break;
        }

        modalIcon.innerHTML = iconHTML;
        modalIcon.className = `flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3 ${iconClass}`;
        modalTitle.textContent = options.title || 'Aviso';
        modalMessage.textContent = options.message || '';

        // Configurar botões
        let buttonsHTML = '';
        if (options.showCancel) {
            buttonsHTML += `
                <button id="modalCancelBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    ${options.cancelText || 'Cancelar'}
                </button>
            `;
        }
        
        const confirmClass = options.type === 'error' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500';
        buttonsHTML += `
            <button id="modalConfirmBtn" class="px-4 py-2 text-sm font-medium text-white ${confirmClass} rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2">
                ${options.confirmText || 'OK'}
            </button>
        `;

        modalButtons.innerHTML = buttonsHTML;

        // Configurar eventos dos botões
        const confirmBtn = document.getElementById('modalConfirmBtn');
        const cancelBtn = document.getElementById('modalCancelBtn');

        confirmBtn.onclick = () => {
            this.hide();
            if (options.onConfirm) options.onConfirm();
        };

        if (cancelBtn) {
            cancelBtn.onclick = () => {
                this.hide();
                if (options.onCancel) options.onCancel();
            };
        }

        // Mostrar modal
        modal.classList.remove('hidden');
        
        // Fechar com ESC
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                this.hide();
                document.removeEventListener('keydown', handleEscape);
                if (options.onCancel) options.onCancel();
            }
        };
        document.addEventListener('keydown', handleEscape);

        // Fechar clicando no backdrop
        modal.onclick = (e) => {
            if (e.target === modal) {
                this.hide();
                if (options.onCancel) options.onCancel();
            }
        };
    }

    hide() {
        const modal = document.getElementById('customModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
}

// Instanciar o modal
const customModal = new CustomModal();

// Funções de conveniência para substituir alert() e confirm()
function showAlert(message, type = 'info', title = 'Aviso') {
    customModal.show({
        type: type,
        title: title,
        message: message,
        showCancel: false,
        confirmText: 'OK'
    });
}

function showConfirm(message, onConfirm, onCancel = null, title = 'Confirmação') {
    customModal.show({
        type: 'warning',
        title: title,
        message: message,
        showCancel: true,
        confirmText: 'Confirmar',
        cancelText: 'Cancelar',
        onConfirm: onConfirm,
        onCancel: onCancel
    });
}

function showError(message, title = 'Erro') {
    customModal.show({
        type: 'error',
        title: title,
        message: message,
        showCancel: false,
        confirmText: 'OK'
    });
}

function showSuccess(message, title = 'Sucesso') {
    customModal.show({
        type: 'success',
        title: title,
        message: message,
        showCancel: false,
        confirmText: 'OK'
    });
}
</script>
@endpush
@endsection