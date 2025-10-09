@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Cliente</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Atualize as informações do cliente</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('clientes.show', $cliente) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </a>
                <a href="{{ route('clientes.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Cliente</h2>
                    
                    <form method="POST" action="{{ route('clientes.update', $cliente) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Avatar -->
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <img id="avatar-preview" 
                         class="h-16 w-16 object-cover rounded-full border-2 border-gray-300 dark:border-gray-600" 
                         src="{{ $cliente->avatar ? asset('storage/' . $cliente->avatar) : 'data:image/svg+xml,%3csvg width=\'100\' height=\'100\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'18\' text-anchor=\'middle\' alignment-baseline=\'middle\' font-family=\'monospace, sans-serif\' fill=\'%236b7280\'%3eAvatar%3c/text%3e%3c/svg%3e' }}" 
                         alt="Avatar preview">
                </div>
                <div class="flex-1">
                    <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Avatar
                    </label>
                    <input type="file" 
                           id="avatar" 
                           name="avatar" 
                           accept="image/*"
                           onchange="previewAvatar(this)"
                           class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PNG, JPG ou GIF até 2MB</p>
                </div>
            </div>
            
            <!-- Informações Básicas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome Completo *
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome', $cliente->nome) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="pessoa_contato" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pessoa de Contato
                    </label>
                    <input type="text" 
                           id="pessoa_contato" 
                           name="pessoa_contato" 
                           value="{{ old('pessoa_contato', $cliente->pessoa_contato) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('pessoa_contato') border-red-500 @enderror">
                    @error('pessoa_contato')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        E-mail
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $cliente->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Contato -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Telefone
                    </label>
                    <input type="tel" 
                           id="telefone" 
                           name="telefone" 
                           value="{{ old('telefone', $cliente->telefone) }}"
                           placeholder="(11) 99999-9999"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telefone') border-red-500 @enderror">
                    @error('telefone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        WhatsApp
                    </label>
                    <input type="tel" 
                           id="whatsapp" 
                           name="whatsapp" 
                           value="{{ old('whatsapp', $cliente->whatsapp) }}"
                           placeholder="(11) 99999-9999"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('whatsapp') border-red-500 @enderror">
                    @error('whatsapp')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('clientes.index') }}" 
                   class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center inline-flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Alterações
                </button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
        <!-- Informações do Cliente -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Card de Informações Atuais -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Atuais</h3>
                    
                    <div class="text-center mb-6">
                        <img class="mx-auto h-24 w-24 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600" 
                             src="{{ $cliente->avatar ? Storage::url($cliente->avatar) : 'data:image/svg+xml,%3csvg width=\'100\' height=\'100\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'18\' text-anchor=\'middle\' alignment-baseline=\'middle\' font-family=\'monospace, sans-serif\' fill=\'%236b7280\'%3e' . strtoupper(substr($cliente->nome, 0, 2)) . '%3c/text%3e%3c/svg%3e' }}" 
                             alt="{{ $cliente->nome }}">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Avatar atual</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nome</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->nome }}</p>
                        </div>
                        
                        @if($cliente->pessoa_contato)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pessoa de Contato</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->pessoa_contato }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->email)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">E-mail</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->email }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->telefone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Telefone</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->telefone }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->whatsapp)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">WhatsApp</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->whatsapp }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Informações do Sistema -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Sistema</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Data de Criação</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Última Atualização</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ID do Cliente</label>
                            <p class="text-gray-900 dark:text-white">#{{ $cliente->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>

<script>
// Phone mask functions
function formatPhone(value) {
    // Remove todos os caracteres não numéricos
    const numbers = value.replace(/\D/g, '');
    
    // Limita a 11 dígitos (DDD + 9 dígitos para celular)
    const limitedNumbers = numbers.substring(0, 11);
    
    // Aplica a formatação baseada no número de dígitos
    if (limitedNumbers.length <= 2) {
        return limitedNumbers;
    } else if (limitedNumbers.length <= 6) {
        return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2)}`;
    } else if (limitedNumbers.length <= 10) {
        // Telefone fixo: (XX) XXXX-XXXX
        return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2, 6)}-${limitedNumbers.substring(6)}`;
    } else {
        // Celular: (XX) XXXXX-XXXX
        return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2, 7)}-${limitedNumbers.substring(7)}`;
    }
}

function applyPhoneMask(input) {
    const cursorPosition = input.selectionStart;
    const oldValue = input.value;
    const oldLength = oldValue.length;
    
    // Aplica a formatação
    const newValue = formatPhone(input.value);
    input.value = newValue;
    
    // Ajusta a posição do cursor
    const newLength = newValue.length;
    const lengthDiff = newLength - oldLength;
    
    // Calcula nova posição do cursor
    let newCursorPosition = cursorPosition + lengthDiff;
    
    // Garante que o cursor não fique em uma posição inválida
    if (newCursorPosition < 0) newCursorPosition = 0;
    if (newCursorPosition > newLength) newCursorPosition = newLength;
    
    // Define a nova posição do cursor
    setTimeout(() => {
        input.setSelectionRange(newCursorPosition, newCursorPosition);
    }, 0);
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar máscaras quando o documento carregar
document.addEventListener('DOMContentLoaded', function() {
    const telefoneInput = document.getElementById('telefone');
    const whatsappInput = document.getElementById('whatsapp');
    
    // Aplicar máscara nos valores iniciais se existirem
    if (telefoneInput && telefoneInput.value) {
        telefoneInput.value = formatPhone(telefoneInput.value);
    }
    
    if (whatsappInput && whatsappInput.value) {
        whatsappInput.value = formatPhone(whatsappInput.value);
    }
    
    // Configurar event listeners para telefone
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            applyPhoneMask(this);
        });
        
        telefoneInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                applyPhoneMask(this);
            }, 0);
        });
    }
    
    // Configurar event listeners para WhatsApp
    if (whatsappInput) {
        whatsappInput.addEventListener('input', function(e) {
            applyPhoneMask(this);
        });
        
        whatsappInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                applyPhoneMask(this);
            }, 0);
        });
    }
});
</script>
@endsection