@extends('layouts.app')

@section('title', 'Configurações de Arquivos Temporários')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Configurações de Arquivos Temporários</h1>
        <p class="text-gray-600 dark:text-gray-400">Gerencie as configurações globais para arquivos temporários e visualize estatísticas do sistema.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Settings Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Configurações Globais</h2>
            
            <form action="{{ route('admin.temp-file-settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="default_expiry_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Dias de Expiração Padrão
                    </label>
                    <input type="number" 
                           id="default_expiry_days" 
                           name="default_expiry_days" 
                           value="{{ old('default_expiry_days', $settings['default_expiry_days']) }}"
                           min="1" 
                           max="365" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           required>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Número padrão de dias antes da expiração de arquivos temporários</p>
                </div>
                
                <div>
                    <label for="max_expiry_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Máximo de Dias de Expiração
                    </label>
                    <input type="number" 
                           id="max_expiry_days" 
                           name="max_expiry_days" 
                           value="{{ old('max_expiry_days', $settings['max_expiry_days']) }}"
                           min="1" 
                           max="365" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           required>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Número máximo de dias que um usuário pode definir para expiração</p>
                </div>
                
                <div>
                    <label for="cleanup_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Horário da Limpeza Automática
                    </label>
                    <input type="time" 
                           id="cleanup_time" 
                           name="cleanup_time" 
                           value="{{ old('cleanup_time', $settings->getCleanupTimeFormatted()) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           required>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Horário diário para executar a limpeza automática</p>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="auto_cleanup_enabled" 
                           name="auto_cleanup_enabled" 
                           value="1"
                           {{ old('auto_cleanup_enabled', $settings['auto_cleanup_enabled']) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700">
                    <label for="auto_cleanup_enabled" class="ml-2 block text-sm text-gray-900 dark:text-white">
                        Habilitar limpeza automática de arquivos expirados
                    </label>
                </div>
                
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-200">
                        Salvar Configurações
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics and Actions -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Estatísticas do Sistema</h2>
                
                <div id="statistics-container" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="total-temp-files">{{ $statistics['total_temporary_files'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Arquivos Temporários</div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="expiring-soon">{{ $statistics['expiring_soon'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Expirando em 24h</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="expired-files">{{ $statistics['expired_files'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Arquivos Expirados</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="total-size">{{ $statistics['total_size_formatted'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Espaço Ocupado</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button onclick="refreshStatistics()" 
                            class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-200">
                        Atualizar Estatísticas
                    </button>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Ações Administrativas</h2>
                
                <div class="space-y-4">
                    <button onclick="triggerCleanup()" 
                            class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-200">
                        Executar Limpeza Manual
                    </button>
                    
                    <button onclick="sendWarnings()" 
                            class="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-200">
                        Enviar Avisos de Expiração
                    </button>
                </div>
                
                <div id="action-result" class="mt-4 hidden">
                    <!-- Action results will be displayed here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshStatistics() {
    fetch('{{ route("admin.temp-file-settings.statistics") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-temp-files').textContent = data.statistics.total_temporary_files;
                document.getElementById('expiring-soon').textContent = data.statistics.expiring_soon || data.statistics.files_expiring_24h;
                document.getElementById('expired-files').textContent = data.statistics.expired_files;
                document.getElementById('total-size').textContent = data.statistics.total_size_formatted;
                
                showActionResult('Estatísticas atualizadas com sucesso!', 'success');
            }
        })
        .catch(error => {
            showActionResult('Erro ao atualizar estatísticas.', 'error');
        });
}

function triggerCleanup() {
    showConfirmModal(
        'Confirmar Limpeza',
        'Tem certeza que deseja executar a limpeza manual? Esta ação irá remover permanentemente todos os arquivos expirados.',
        function() {
            fetch('{{ route("admin.temp-file-settings.cleanup") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showActionResult(data.message + (data.freed_space ? ` Espaço liberado: ${data.freed_space}` : ''), 'success');
                    refreshStatistics();
                } else {
                    showActionResult(data.message, 'error');
                }
            })
            .catch(error => {
                showActionResult('Erro durante a limpeza: ' + error.message, 'error');
            });
        }
    );
}

function sendWarnings() {
    fetch('{{ route("admin.temp-file-settings.warnings") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showActionResult(data.message, 'success');
        } else {
            showActionResult(data.message, 'error');
        }
    })
    .catch(error => {
        showActionResult('Erro ao enviar avisos.', 'error');
    });
}

function showActionResult(message, type) {
    const resultDiv = document.getElementById('action-result');
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    
    resultDiv.innerHTML = `
        <div class="${bgColor} border px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">${message}</span>
        </div>
    `;
    
    resultDiv.classList.remove('hidden');
    
    // Hide after 5 seconds
    setTimeout(() => {
        resultDiv.classList.add('hidden');
    }, 5000);
}

function showConfirmModal(title, message, onConfirm) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10003;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    // Create modal content
    const modal = document.createElement('div');
    const isDarkMode = document.documentElement.classList.contains('dark');
    modal.style.cssText = `
        background: ${isDarkMode ? '#1f2937' : 'white'};
        border-radius: 8px;
        padding: 24px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    `;
    
    modal.innerHTML = `
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 16px; color: ${isDarkMode ? '#f9fafb' : '#1f2937'};">${title}</h3>
        <p style="color: ${isDarkMode ? '#d1d5db' : '#6b7280'}; margin-bottom: 24px; line-height: 1.5;">${message}</p>
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button id="modal-cancel" style="
                padding: 8px 16px;
                border: 1px solid ${isDarkMode ? '#4b5563' : '#d1d5db'};
                background: ${isDarkMode ? '#374151' : 'white'};
                color: ${isDarkMode ? '#f9fafb' : '#374151'};
                border-radius: 6px;
                cursor: pointer;
                font-weight: 500;
            ">Cancelar</button>
            <button id="modal-confirm" style="
                padding: 8px 16px;
                border: none;
                background: #dc2626;
                color: white;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 500;
            ">Confirmar</button>
        </div>
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Add event listeners
    const cancelBtn = modal.querySelector('#modal-cancel');
    const confirmBtn = modal.querySelector('#modal-confirm');
    
    function closeModal() {
        document.body.removeChild(overlay);
    }
    
    cancelBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeModal();
    });
    
    confirmBtn.addEventListener('click', function() {
        closeModal();
        onConfirm();
    });
    
    // Add hover effects
    cancelBtn.addEventListener('mouseenter', function() {
        this.style.backgroundColor = isDarkMode ? '#4b5563' : '#f9fafb';
    });
    cancelBtn.addEventListener('mouseleave', function() {
        this.style.backgroundColor = isDarkMode ? '#374151' : 'white';
    });
    
    confirmBtn.addEventListener('mouseenter', function() {
        this.style.backgroundColor = '#b91c1c';
    });
    confirmBtn.addEventListener('mouseleave', function() {
        this.style.backgroundColor = '#dc2626';
    });
}
</script>
@endsection