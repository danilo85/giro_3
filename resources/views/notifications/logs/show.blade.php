@extends('layouts.app')

@section('title', 'Detalhes do Log de Notificação')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes do Log de Notificação</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Informações detalhadas sobre o envio da notificação</p>
            </div>
            <div class="flex space-x-3">
                @if($log->isFailed())
                    <button onclick="retryLog('{{ $log->id }}')" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-redo mr-2"></i>
                        Tentar Novamente
                    </button>
                @endif
                <a href="{{ route('notifications.logs.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Voltar para Logs">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Log Details -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informações do Log</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID do Log</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $log->id }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Canal</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($log->channel === 'email') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($log->channel === 'sms') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                    @endif">
                                    {{ $log->getChannelLabel() }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($log->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($log->status === 'sent') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($log->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                    @endif">
                                    {{ $log->getStatusLabel() }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Enviado em</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $log->sent_at ? $log->sent_at->format('d/m/Y H:i:s') : 'Não enviado' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Entregue em</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $log->delivered_at ? $log->delivered_at->format('d/m/Y H:i:s') : 'Não entregue' }}
                            </dd>
                        </div>

                        @if($log->error_message)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mensagem de Erro</dt>
                                <dd class="mt-1 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-3 rounded-md">
                                    {{ $log->error_message }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Timeline do Log</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list">
                            <!-- Created -->
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <i class="fas fa-plus text-white text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Log criado</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            @if($log->sent_at)
                                <!-- Sent -->
                                <li>
                                    <div class="relative pb-8">
                                        @if($log->delivered_at || $log->isFailed())
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <i class="fas fa-paper-plane text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Notificação enviada</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    {{ $log->sent_at->format('d/m/Y H:i:s') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if($log->delivered_at)
                                <!-- Delivered -->
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Notificação entregue</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    {{ $log->delivered_at->format('d/m/Y H:i:s') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @elseif($log->isFailed())
                                <!-- Failed -->
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <i class="fas fa-times text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Falha no envio</p>
                                                    @if($log->error_message)
                                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $log->error_message }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    {{ $log->updated_at ? $log->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Details -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detalhes da Notificação</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Título</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->notification->title }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                            <dd class="mt-1">
                                <span class="notification-type-badge px-2 py-1 text-xs font-medium rounded-full {{ $log->notification->getTypeBadgeClass() }}">
                                    {{ $log->notification->getTypeLabel() }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mensagem</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->notification->message }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status da Notificação</dt>
                            <dd class="mt-1">
                                @if($log->notification->isRead())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Lida
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Não lida
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Criada em</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->notification->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>

                        @if($log->notification->read_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lida em</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->notification->read_at->format('d/m/Y H:i:s') }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('notifications.show', $log->notification) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors text-center block">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Notificação Completa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function retryLog(logId) {
    if (!confirm('Tem certeza que deseja tentar enviar esta notificação novamente?')) {
        return;
    }

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
            alert('Notificação reenviada com sucesso!');
            location.reload();
        } else {
            alert('Erro ao reenviar notificação: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao reenviar notificação.');
    });
}
</script>
@endpush
@endsection