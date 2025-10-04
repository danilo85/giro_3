@extends('layouts.app')

@section('title', 'Preferências de Notificação')

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
        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white dark:bg-blue-700 dark:text-blue-200">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Preferências
        </span>
        <a href="{{ route('notifications.logs.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Logs
        </a>
    </div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Preferências de Notificação</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Configure como e quando você deseja receber notificações</p>
        </div>
        <a href="{{ route('notifications.index') }}" 
           class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors mt-4 sm:mt-0">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-check-circle mr-2"></i>
                </div>
                <div>
                    <p class="font-bold">Sucesso!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                </div>
                <div>
                    <p class="font-bold">Erro!</p>
                    <ul class="text-sm list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('notifications.preferences.update') }}">
        @csrf

        <!-- General Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Configurações Gerais</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure suas preferências globais de notificação</p>
            </div>
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="email_enabled" class="text-sm font-medium text-gray-900 dark:text-white">
                            Notificações por Email
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Receber notificações por email quando eventos importantes acontecerem
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_enabled" id="email_enabled" value="1" 
                                   class="sr-only peer" {{ $preferences['email_enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="system_enabled" class="text-sm font-medium text-gray-900 dark:text-white">
                            Notificações do Sistema
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Receber notificações dentro do sistema
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="system_enabled" id="system_enabled" value="1" 
                                   class="sr-only peer" {{ $preferences['system_enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Notifications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notificações de Orçamentos</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure quando receber notificações sobre mudanças de status dos orçamentos</p>
            </div>
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="budget_status_change" class="text-sm font-medium text-gray-900 dark:text-white">
                            Mudanças de Status
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Notificar quando o status de um orçamento for alterado
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="budget_status_change" id="budget_status_change" value="1" 
                                   class="sr-only peer" {{ $preferences['budget_status_change'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="budget_approved" class="text-sm font-medium text-gray-900 dark:text-white">
                            Orçamentos Aprovados
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Notificar quando um orçamento for aprovado
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="budget_approved" id="budget_approved" value="1" 
                                   class="sr-only peer" {{ $preferences['budget_approved'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="budget_rejected" class="text-sm font-medium text-gray-900 dark:text-white">
                            Orçamentos Rejeitados
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Notificar quando um orçamento for rejeitado
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="budget_rejected" id="budget_rejected" value="1" 
                                   class="sr-only peer" {{ $preferences['budget_rejected'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="budget_paid" class="text-sm font-medium text-gray-900 dark:text-white">
                            Orçamentos Pagos
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Notificar quando um orçamento for totalmente pago
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="budget_paid" id="budget_paid" value="1" 
                                   class="sr-only peer" {{ $preferences['budget_paid'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Notifications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notificações de Pagamentos</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure alertas sobre vencimentos e pagamentos</p>
            </div>
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="payment_due_alerts" class="text-sm font-medium text-gray-900 dark:text-white">
                            Alertas de Vencimento
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Receber alertas sobre pagamentos próximos do vencimento
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="payment_due_alerts" id="payment_due_alerts" value="1" 
                                   class="sr-only peer" {{ $preferences['payment_due_alerts'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="payment_due_days_7" class="flex items-center">
                            <input type="checkbox" name="payment_due_days[]" id="payment_due_days_7" value="7" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   {{ in_array('7', $preferences['payment_due_days'] ?? []) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-900 dark:text-white">7 dias antes</span>
                        </label>
                    </div>
                    <div>
                        <label for="payment_due_days_3" class="flex items-center">
                            <input type="checkbox" name="payment_due_days[]" id="payment_due_days_3" value="3" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   {{ in_array('3', $preferences['payment_due_days'] ?? []) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-900 dark:text-white">3 dias antes</span>
                        </label>
                    </div>
                    <div>
                        <label for="payment_due_days_1" class="flex items-center">
                            <input type="checkbox" name="payment_due_days[]" id="payment_due_days_1" value="1" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   {{ in_array('1', $preferences['payment_due_days'] ?? []) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-900 dark:text-white">1 dia antes</span>
                        </label>
                    </div>
                    <div>
                        <label for="payment_due_days_0" class="flex items-center">
                            <input type="checkbox" name="payment_due_days[]" id="payment_due_days_0" value="0" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   {{ in_array('0', $preferences['payment_due_days'] ?? []) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-900 dark:text-white">No dia do vencimento</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label for="payment_overdue_alerts" class="text-sm font-medium text-gray-900 dark:text-white">
                            Alertas de Atraso
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Notificar sobre pagamentos em atraso
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="payment_overdue_alerts" id="payment_overdue_alerts" value="1" 
                                   class="sr-only peer" {{ $preferences['payment_overdue_alerts'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6" id="email-settings">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Configurações de Email</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure como receber emails de notificação</p>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="email_frequency" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Frequência de Emails
                    </label>
                    <select name="email_frequency" id="email_frequency" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="immediate" {{ ($preferences['email_frequency'] ?? 'immediate') === 'immediate' ? 'selected' : '' }}>
                            Imediato (assim que ocorrer)
                        </option>
                        <option value="daily" {{ ($preferences['email_frequency'] ?? 'immediate') === 'daily' ? 'selected' : '' }}>
                            Resumo diário
                        </option>
                        <option value="weekly" {{ ($preferences['email_frequency'] ?? 'immediate') === 'weekly' ? 'selected' : '' }}>
                            Resumo semanal
                        </option>
                    </select>
                </div>

                <div>
                    <label for="email_time" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Horário Preferido (para resumos)
                    </label>
                    <input type="time" name="email_time" id="email_time" 
                           value="{{ $preferences['email_time'] ?? '09:00:00' }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>
                Salvar Preferências
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailEnabled = document.getElementById('email_enabled');
    const emailSettings = document.getElementById('email-settings');
    
    function toggleEmailSettings() {
        if (emailEnabled.checked) {
            emailSettings.style.opacity = '1';
            emailSettings.style.pointerEvents = 'auto';
        } else {
            emailSettings.style.opacity = '0.5';
            emailSettings.style.pointerEvents = 'none';
        }
    }
    
    emailEnabled.addEventListener('change', toggleEmailSettings);
    toggleEmailSettings(); // Initial state
    
    const paymentDueAlerts = document.getElementById('payment_due_alerts');
    const paymentDueDaysContainer = paymentDueAlerts.closest('.space-y-6').querySelector('.grid');
    
    function togglePaymentDueDays() {
        if (paymentDueAlerts.checked) {
            paymentDueDaysContainer.style.opacity = '1';
            paymentDueDaysContainer.style.pointerEvents = 'auto';
        } else {
            paymentDueDaysContainer.style.opacity = '0.5';
            paymentDueDaysContainer.style.pointerEvents = 'none';
        }
    }
    
    paymentDueAlerts.addEventListener('change', togglePaymentDueDays);
    togglePaymentDueDays(); // Initial state
});
</script>
@endpush
@endsection