@extends('layouts.app')

@section('title', 'Detalhes da Notificação')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes da Notificação</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Visualize os detalhes completos da notificação</p>
        </div>
        <div class="flex gap-3 mt-4 sm:mt-0">
            <a href="{{ route('notifications.index') }}" 
               class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            @if(!$notification->read_at)
                <button onclick="markAsRead()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    Marcar como Lida
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Notification Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="notification-type-badge px-3 py-1 text-sm font-medium rounded-full {{ $notification->getTypeBadgeClass() }}">
                                {{ $notification->getTypeLabel() }}
                            </span>
                            @if(!$notification->read_at)
                                <span class="flex items-center gap-1 text-sm text-blue-600 dark:text-blue-400">
                                    <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                    Não lida
                                </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $notification->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $notification->title }}
                    </h2>

                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            {{ $notification->message }}
                        </p>
                    </div>

                    @if($notification->data && is_array($notification->data))
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Dados Adicionais</h4>
                            <div class="space-y-2">
                                @foreach($notification->data as $key => $value)
                                    @if(!in_array($key, ['action_url', 'action_text']))
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">
                                                {{ str_replace('_', ' ', $key) }}:
                                            </span>
                                            <span class="text-sm text-gray-900 dark:text-white font-medium">
                                                @if(is_array($value))
                                                    {{ json_encode($value) }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($notification->getActionUrl())
                        <div class="mt-6">
                            <a href="{{ $notification->getActionUrl() }}" 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                {{ $notification->getActionText() }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Information -->
            @if($notification->type === 'budget_status_changed' && isset($notification->data['budget_id']))
                @php
                    $budget = \App\Models\Orcamento::find($notification->data['budget_id']);
                @endphp
                @if($budget)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Orçamento Relacionado</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $budget->numero }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $budget->cliente->nome ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Atual</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $budget->getStatusBadgeClass() }}">
                                        {{ $budget->getStatusLabel() }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Total</label>
                                    <p class="text-sm text-gray-900 dark:text-white">R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('orcamentos.show', $budget) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Orçamento Completo
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if($notification->type === 'payment_due_alert' && isset($notification->data['budget_id']))
                @php
                    $budget = \App\Models\Orcamento::find($notification->data['budget_id']);
                @endphp
                @if($budget)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informações de Pagamento</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Orçamento</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $budget->numero }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $budget->cliente->nome ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Vencimento</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $budget->data_vencimento ? $budget->data_vencimento->format('d/m/Y') : 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Pendente</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        R$ {{ number_format($budget->valor_total - $budget->valor_pago, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('pagamentos.create', ['orcamento_id' => $budget->id]) }}" 
                                   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors mr-3">
                                    <i class="fas fa-plus mr-2"></i>
                                    Registrar Pagamento
                                </a>
                                <a href="{{ route('orcamentos.show', $budget) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Orçamento
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Notification Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informações</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <span class="inline-flex items-center gap-2">
                            @if($notification->read_at)
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="text-sm text-green-600 dark:text-green-400">Lida</span>
                            @else
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span class="text-sm text-blue-600 dark:text-blue-400">Não lida</span>
                            @endif
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Criada em</label>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ $notification->created_at->format('d/m/Y H:i:s') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($notification->read_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lida em</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $notification->read_at->format('d/m/Y H:i:s') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $notification->read_at->diffForHumans() }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID</label>
                        <p class="text-sm text-gray-900 dark:text-white font-mono">
                            {{ $notification->id }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ações</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if(!$notification->read_at)
                        <button onclick="markAsRead()" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Marcar como Lida
                        </button>
                    @else
                        <button onclick="markAsUnread()" 
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-undo mr-2"></i>
                            Marcar como Não Lida
                        </button>
                    @endif

                    <button onclick="deleteNotification()" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Excluir Notificação
                    </button>

                    <a href="{{ route('notifications.preferences') }}" 
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-block text-center">
                        <i class="fas fa-cog mr-2"></i>
                        Configurar Notificações
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead() {
    const xhr = new XMLHttpRequest();
    xhr.open('PATCH', `/notifications/{{ $notification->id }}/read`, true);
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
                        alert('Erro ao marcar notificação como lida');
                    }
                } catch (e) {
                    alert('Erro ao marcar notificação como lida');
                }
            } else {
                alert('Erro ao marcar notificação como lida');
            }
        }
    };
    
    xhr.send();
}

function markAsUnread() {
    const xhr = new XMLHttpRequest();
    xhr.open('PATCH', `/notifications/{{ $notification->id }}/unread`, true);
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
                        alert('Erro ao marcar notificação como não lida');
                    }
                } catch (e) {
                    alert('Erro ao marcar notificação como não lida');
                }
            } else {
                alert('Erro ao marcar notificação como não lida');
            }
        }
    };
    
    xhr.send();
}

function deleteNotification() {
    if (confirm('Tem certeza que deseja excluir esta notificação?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('DELETE', `/notifications/{{ $notification->id }}`, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.setRequestHeader('Content-Type', 'application/json');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            window.location.href = '{{ route("notifications.index") }}';
                        } else {
                            alert('Erro ao excluir notificação');
                        }
                    } catch (e) {
                        alert('Erro ao excluir notificação');
                    }
                } else {
                    alert('Erro ao excluir notificação');
                }
            }
        };
        
        xhr.send();
    }
}
</script>
@endpush
@endsection