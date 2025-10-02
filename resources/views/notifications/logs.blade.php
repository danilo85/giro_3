@extends('layouts.app')

@section('title', 'Logs de Notificação')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Logs de Notificação</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Monitore o histórico de envio de notificações</p>
        </div>
        <div class="flex gap-3 mt-4 sm:mt-0">
            <a href="{{ route('notifications.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar às Notificações
            </a>
            <button onclick="refreshStats()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>
                Atualizar
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" id="stats-container">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-paper-plane text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Enviados</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sucessos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['success'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Falhas</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['failed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fas fa-percentage text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Taxa de Sucesso</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['success_rate'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Filtros</h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('notification-logs.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <label for="channel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Canal</label>
                    <select name="channel" id="channel" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os canais</option>
                        <option value="email" {{ request('channel') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="system" {{ request('channel') === 'system' ? 'selected' : '' }}>Sistema</option>
                    </select>
                </div>

                <div class="flex-1 min-w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os status</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Enviado</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Falhou</option>
                    </select>
                </div>

                <div class="flex-1 min-w-48">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Inicial</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex-1 min-w-48">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Final</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrar
                    </button>
                </div>

                @if(request()->hasAny(['channel', 'status', 'date_from', 'date_to']))
                    <div class="flex items-end">
                        <a href="{{ route('notification-logs.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Limpar
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Histórico de Logs</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Notificação
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Canal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Destinatário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Data/Hora
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ Str::limit($log->notification->title ?? 'N/A', 40) }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $log->notification->getTypeLabel() ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $log->getChannelBadgeClass() }}">
                                    {{ ucfirst($log->channel) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $log->getStatusBadgeClass() }}">
                                    {{ $log->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $log->recipient }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div>{{ $log->sent_at ? $log->sent_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                <div class="text-xs">{{ $log->sent_at ? $log->sent_at->diffForHumans() : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button onclick="showLogDetails('{{ $log->id }}')" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                            title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($log->status === 'failed')
                                        <button onclick="retryLog('{{ $log->id }}')" 
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" 
                                                title="Tentar novamente">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-alt text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum log encontrado</h3>
                                <p class="text-gray-600 dark:text-gray-400">Não há logs de notificação para exibir.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Log Details Modal -->
<div id="logDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detalhes do Log</h3>
                <button onclick="closeLogDetails()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="logDetailsContent" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showLogDetails(logId) {
    fetch(`/notification-logs/${logId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const log = data.log;
                const content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID</label>
                            <p class="text-sm text-gray-900 dark:text-white font-mono">${log.id}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Canal</label>
                            <p class="text-sm text-gray-900 dark:text-white">${log.channel}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <p class="text-sm text-gray-900 dark:text-white">${log.status}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Destinatário</label>
                            <p class="text-sm text-gray-900 dark:text-white">${log.recipient}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enviado em</label>
                            <p class="text-sm text-gray-900 dark:text-white">${log.sent_at || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tentativas</label>
                            <p class="text-sm text-gray-900 dark:text-white">${log.attempts}</p>
                        </div>
                    </div>
                    ${log.error_message ? `
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mensagem de Erro</label>
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-3">
                                <p class="text-sm text-red-800 dark:text-red-200">${log.error_message}</p>
                            </div>
                        </div>
                    ` : ''}
                    ${log.metadata ? `
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metadados</label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-3">
                                <pre class="text-sm text-gray-900 dark:text-white overflow-x-auto">${JSON.stringify(log.metadata, null, 2)}</pre>
                            </div>
                        </div>
                    ` : ''}
                `;
                
                document.getElementById('logDetailsContent').innerHTML = content;
                document.getElementById('logDetailsModal').classList.remove('hidden');
            } else {
                alert('Erro ao carregar detalhes do log');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao carregar detalhes do log');
        });
}

function closeLogDetails() {
    document.getElementById('logDetailsModal').classList.add('hidden');
}

function retryLog(logId) {
    if (confirm('Tem certeza que deseja tentar reenviar esta notificação?')) {
        fetch(`/notification-logs/${logId}/retry`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notificação reenviada com sucesso!');
                location.reload();
            } else {
                alert('Erro ao reenviar notificação: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao reenviar notificação');
        });
    }
}

function refreshStats() {
    fetch('/notification-logs/stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update stats in the UI
                const statsContainer = document.getElementById('stats-container');
                const stats = data.stats;
                
                statsContainer.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <i class="fas fa-paper-plane text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Enviados</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${stats.total}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sucessos</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${stats.success}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Falhas</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${stats.failed}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <i class="fas fa-percentage text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Taxa de Sucesso</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${stats.success_rate}%</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Close modal when clicking outside
document.getElementById('logDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogDetails();
    }
});
</script>
@endpush
@endsection