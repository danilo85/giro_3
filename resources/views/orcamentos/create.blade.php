@extends('layouts.app')

@section('title', 'Novo Orçamento')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo Orçamento</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Crie um novo orçamento para seus clientes</p>
        </div>
        <a href="{{ route('orcamentos.index') }}" 
           class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" 
           title="Voltar">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('orcamentos.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Informações Básicas -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Informações Básicas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Título -->
                    <div class="md:col-span-2">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Título do Orçamento *
                        </label>
                        <input type="text" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('titulo') border-red-500 @enderror">
                        @error('titulo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cliente -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Buscar e selecionar clientes *
                        </label>
                        
                        <!-- Campo de busca com autocomplete -->
                        <div class="mb-4">
                            <div class="relative">
                                <input type="text" 
                                       id="cliente_autocomplete" 
                                       placeholder="Digite o nome do cliente para buscar ou criar novo..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <input type="hidden" 
                                       id="cliente_id" 
                                       name="cliente_id" 
                                       value="{{ old('cliente_id') }}">
                            </div>
                        </div>
                        
                        <!-- Container para clientes selecionados -->
                        <div id="clientes_container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            <!-- Clientes selecionados aparecerão aqui -->
                        </div>
                        
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Digite para buscar clientes existentes ou criar um novo automaticamente
                        </div>
                        @error('cliente_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('clientes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-2">
                            <a href="{{ route('clientes.create') }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                + Cadastrar novo cliente
                            </a>
                        </div>
                    </div>

                    <!-- Modelo de Proposta -->
                    <div>
                        <label for="modelo_proposta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Modelo de Proposta
                        </label>
                        <select id="modelo_proposta_id" 
                                name="modelo_proposta_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecione um modelo (opcional)</option>
                            @foreach($modelos as $modelo)
                                <option value="{{ $modelo->id }}" 
                                        data-conteudo="{{ $modelo->conteudo }}"
                                        data-valor="{{ $modelo->valor_padrao }}"
                                        data-prazo="{{ $modelo->prazo_padrao }}"
                                        {{ old('modelo_proposta_id') == $modelo->id ? 'selected' : '' }}>
                                    {{ $modelo->nome }} - {{ $modelo->categoria }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <a href="{{ route('modelos-propostas.create') }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                + Criar novo modelo
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Conteúdo da Proposta
                </h3>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição/Conteúdo *
                    </label>
                    
                    <!-- Editor de Texto Rico -->
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden @error('descricao') border-red-500 @enderror">
                        <!-- Barra de Ferramentas -->
                        <div class="bg-gray-50 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600 p-2 sm:p-3 flex flex-wrap gap-1 sm:gap-2">
                            <button type="button" onclick="formatText('bold')" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Negrito">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Itálico">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Sublinhado">
                                <i class="fas fa-underline"></i>
                            </button>
                            <button type="button" onclick="formatText('strikeThrough')" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Riscado">
                                <i class="fas fa-strikethrough"></i>
                            </button>
                            <div class="w-px bg-gray-300 dark:bg-gray-600 mx-1 hidden xs:block"></div>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Lista com marcadores">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" onclick="formatText('insertOrderedList')" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Lista numerada">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <div class="w-px bg-gray-300 dark:bg-gray-600 mx-1 hidden sm:block"></div>
                            <button type="button" onclick="showLinkModal()" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Inserir link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="insertLineBreak()" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Quebra de linha">
                                <i class="fas fa-level-down-alt"></i>
                            </button>
                            <div class="w-px bg-gray-300 dark:bg-gray-600 mx-1 hidden sm:block"></div>
                            <button type="button" onclick="undoEdit()" class="min-w-[36px] min-h-[36px] p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300" title="Desfazer">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                        
                        <!-- Área Editável -->
                        <div id="descricao-editor" 
                             contenteditable="true" 
                             class="min-h-[200px] p-3 focus:outline-none focus:ring-0 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                             placeholder="Descreva detalhadamente o projeto, escopo, entregas, etc."
                             style="white-space: pre-wrap;">{{ old('descricao') }}</div>
                    </div>
                    
                    <!-- Campo oculto para armazenar o conteúdo -->
                    <input type="hidden" id="descricao" name="descricao" value="{{ old('descricao') }}" required>
                    
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Valores e Prazos -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Valores e Prazos
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Valor Total -->
                    <div>
                        <label for="valor_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Valor Total (R$) *
                        </label>
                        <input type="text" 
                               id="valor_total" 
                               name="valor_total" 
                               value="{{ old('valor_total') }}"
                               required
                               placeholder="0,00"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('valor_total') border-red-500 @enderror">
                        @error('valor_total')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prazo -->
                    <div>
                        <label for="prazo_dias" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Prazo (dias)
                        </label>
                        <input type="number" 
                               id="prazo_dias" 
                               name="prazo_dias" 
                               value="{{ old('prazo_dias') }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('prazo_dias') border-red-500 @enderror">
                        @error('prazo_dias')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data do Orçamento -->
                    <div>
                        <label for="data_orcamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Data do Orçamento *
                        </label>
                        <input type="date" 
                               id="data_orcamento" 
                               name="data_orcamento" 
                               value="{{ old('data_orcamento', date('Y-m-d')) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('data_orcamento') border-red-500 @enderror">
                        @error('data_orcamento')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data de Validade -->
                    <div>
                        <label for="data_validade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Data de Validade
                        </label>
                        <input type="date" 
                               id="data_validade" 
                               name="data_validade" 
                               value="{{ old('data_validade') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('data_validade') border-red-500 @enderror">
                        @error('data_validade')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status *
                        </label>
                        <div class="relative">
                            <select id="status" 
                                    name="status" 
                                    required
                                    onchange="updateStatusColors(this)"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm appearance-none pr-8
                                    @if(old('status', 'rascunho') === 'analisando') border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20
                                    @else border-gray-500 bg-gray-50 dark:bg-gray-900/20
                                    @endif @error('status') border-red-500 @enderror">
                                <option value="rascunho" {{ old('status', 'rascunho') == 'rascunho' ? 'selected' : '' }}>⚪ Rascunho</option>
                                <option value="analisando" {{ old('status') == 'analisando' ? 'selected' : '' }}>🟡 Analisando</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Autores -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Autores Responsáveis
                </h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Buscar e selecionar autores
                    </label>
                    
                    <!-- Campo de busca com autocomplete -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                   id="autor_autocomplete" 
                                   placeholder="Digite o nome do autor para buscar ou criar novo..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <!-- Container para autores selecionados -->
                    <div id="autores_container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                        <!-- Autores selecionados aparecerão aqui -->
                    </div>
                    

                    @error('autores')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="mt-2">
                        <a href="{{ route('autores.create') }}" 
                           class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            + Cadastrar novo autor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upload de Arquivos -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Anexos e Documentos
                </h3>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Você poderá anexar arquivos após criar o orçamento. Os arquivos podem incluir propostas, contratos, imagens, etc.
                    </p>
                    <div class="flex items-center text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        <span class="text-sm font-medium">Upload disponível após criação</span>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Informações Adicionais
                </h3>

                <div>
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Observações Internas
                    </label>
                    <textarea id="observacoes" 
                              name="observacoes" 
                              rows="4" 
                              placeholder="Observações internas sobre o orçamento (não visível para o cliente)"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror">{{ old('observacoes') }}</textarea>
                    @error('observacoes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('orcamentos.index') }}" 
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Criar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/cliente-autocomplete.js') }}"></script>
<script src="{{ asset('js/autor-autocomplete.js') }}"></script>
<script>
// Editor de Texto Rico - Funções
function formatText(command, value = null) {
    document.execCommand(command, false, value);
    syncEditorContent();
}

function syncEditorContent() {
    const editor = document.getElementById('descricao-editor');
    const hiddenInput = document.getElementById('descricao');
    hiddenInput.value = editor.innerHTML;
}

function showLinkModal() {
    const url = prompt('Digite a URL do link:');
    if (url) {
        formatText('createLink', url);
    }
}

function insertLineBreak() {
    formatText('insertHTML', '<br>');
}

function undoEdit() {
    formatText('undo');
}

// Sincronizar conteúdo do editor com o campo oculto
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('descricao-editor');
    const hiddenInput = document.getElementById('descricao');
    
    // Sincronizar quando o conteúdo do editor mudar
    editor.addEventListener('input', syncEditorContent);
    editor.addEventListener('paste', function() {
        setTimeout(syncEditorContent, 10);
    });
    
    // Placeholder behavior
    editor.addEventListener('focus', function() {
        if (this.innerHTML === '') {
            this.innerHTML = '';
        }
    });
    
    editor.addEventListener('blur', function() {
        if (this.innerHTML.trim() === '' || this.innerHTML === '<br>') {
            this.innerHTML = '';
        }
        syncEditorContent();
    });
    
    // Sincronizar conteúdo inicial
    if (hiddenInput.value) {
        editor.innerHTML = hiddenInput.value;
    }
});
// Auto-fill from template
document.getElementById('modelo_proposta_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        // Fill content
        const conteudo = selectedOption.dataset.conteudo;
        if (conteudo) {
            document.getElementById('descricao').value = conteudo;
            // Atualizar também o editor visual
            const editor = document.getElementById('descricao-editor');
            if (editor) {
                editor.innerHTML = conteudo;
                syncEditorContent();
            }
        }
        
        // Fill default value
        const valor = selectedOption.dataset.valor;
        if (valor) {
            document.getElementById('valor_total').value = valor;
        }
        
        // Fill default deadline
        const prazo = selectedOption.dataset.prazo;
        if (prazo) {
            document.getElementById('prazo_dias').value = prazo;
        }
        
        // Select default authors
        const autores = JSON.parse(selectedOption.dataset.autores || '[]');
        const checkboxes = document.querySelectorAll('input[name="autores[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = autores.includes(parseInt(checkbox.value));
        });
    }
});

// Set default validity date (30 days from now)
if (!document.getElementById('data_validade').value) {
    const today = new Date();
    today.setDate(today.getDate() + 30);
    document.getElementById('data_validade').value = today.toISOString().split('T')[0];
}

// Função para aplicar máscara de moeda
function applyCurrencyMask(element) {
    let value = element.value.replace(/\D/g, '');
    
    if (!value) {
        element.value = '';
        return;
    }
    
    // Converte para número e divide por 100 para ter centavos
    let number = parseInt(value) / 100;
    
    // Formata no padrão brasileiro
    element.value = number.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Função para atualizar cores do select de status
function updateStatusColors(select) {
    // Remove todas as classes de cor
    select.classList.remove(
        'border-green-500', 'bg-green-50', 'dark:bg-green-900/20',
        'border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20',
        'border-red-500', 'bg-red-50', 'dark:bg-red-900/20',
        'border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20',
        'border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20',
        'border-gray-500', 'bg-gray-50', 'dark:bg-gray-900/20'
    );
    
    // Adiciona classes baseadas no valor selecionado
    const value = select.value;
    switch(value) {
        case 'aprovado':
            select.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
            break;
        case 'analisando':
            select.classList.add('border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20');
            break;
        case 'rejeitado':
            select.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
            break;
        case 'pago':
            select.classList.add('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
            break;
        case 'finalizado':
            select.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            break;
        default: // rascunho
            select.classList.add('border-gray-500', 'bg-gray-50', 'dark:bg-gray-900/20');
            break;
    }
}

// Aplicar máscara de moeda no campo valor_total
const valorTotalInput = document.getElementById('valor_total');
if (valorTotalInput) {
    valorTotalInput.addEventListener('input', function(e) {
        applyCurrencyMask(e.target);
    });
    
    // Aplicar máscara no valor inicial se existir
    if (valorTotalInput.value) {
        applyCurrencyMask(valorTotalInput);
    }
}

// Converter valor formatado para decimal antes do envio do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const valorInput = document.getElementById('valor_total');
    if (valorInput && valorInput.value) {
        // Converter de formato brasileiro para decimal
        const valorDecimal = valorInput.value.replace(/\./g, '').replace(',', '.');
        valorInput.value = valorDecimal;
    }
    
    // Sincronizar conteúdo do editor antes do envio
    syncEditorContent();
});
</script>
@endpush
@endsection