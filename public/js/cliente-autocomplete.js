class ClienteAutocomplete {
    constructor(inputElement, hiddenElement) {
        this.input = inputElement;
        this.hidden = hiddenElement;
        this.dropdown = null;
        this.selectedClientes = [];
        this.debounceTimer = null;
        this.container = document.getElementById('clientes_container');
        
        this.init();
    }
    
    init() {
        this.createDropdown();
        this.bindEvents();
    }
    
    createDropdown() {
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'autocomplete-dropdown';
        this.dropdown.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        `;
        
        this.input.parentNode.style.position = 'relative';
        this.input.parentNode.appendChild(this.dropdown);
    }
    
    bindEvents() {
        this.input.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.search(e.target.value);
            }, 300);
        });
        
        this.input.addEventListener('focus', () => {
            if (this.input.value.length > 0) {
                this.search(this.input.value);
            }
        });
        
        this.input.addEventListener('blur', (e) => {
            // Delay para permitir clique nos itens do dropdown
            setTimeout(() => {
                this.hideDropdown();
            }, 150);
        });
        
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.handleEnter();
            } else if (e.key === 'Escape') {
                this.hideDropdown();
            }
        });
        
        document.addEventListener('click', (e) => {
            if (!this.input.parentNode.contains(e.target)) {
                this.hideDropdown();
            }
        });
    }
    
    async search(query) {
        if (query.length < 2) {
            this.hideDropdown();
            return;
        }
        
        try {
            const response = await fetch(`/api/budget/clientes/autocomplete?q=${encodeURIComponent(query)}`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                console.error('Erro na resposta:', response.status, response.statusText);
                this.hideDropdown();
                return;
            }
            
            const clientes = await response.json();
            
            // Verificar se clientes é um array válido
            if (!Array.isArray(clientes)) {
                console.error('Resposta não é um array:', clientes);
                this.hideDropdown();
                return;
            }
            
            this.showResults(clientes, query);
        } catch (error) {
            console.error('Erro na busca de clientes:', error);
            this.hideDropdown();
        }
    }
    
    showResults(clientes, query) {
        this.dropdown.innerHTML = '';
        
        // Verificar se clientes é um array válido
        if (!Array.isArray(clientes)) {
            console.error('clientes não é um array:', clientes);
            return;
        }
        
        if (clientes.length === 0) {
            // Mostrar opção para criar novo cliente
            const newItem = this.createNewClienteItem(query);
            this.dropdown.appendChild(newItem);
        } else {
            // Mostrar clientes encontrados
            clientes.forEach(cliente => {
                const item = this.createClienteItem(cliente);
                this.dropdown.appendChild(item);
            });
            
            // Adicionar opção para criar novo cliente no final
            const newItem = this.createNewClienteItem(query);
            this.dropdown.appendChild(newItem);
        }
        
        this.showDropdown();
    }
    
    createClienteItem(cliente) {
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.style.cssText = `
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.15s;
        `;
        
        item.innerHTML = `
            <div class="font-medium text-gray-900">${cliente.nome}</div>
            ${cliente.email ? `<div class="text-sm text-gray-500">${cliente.email}</div>` : ''}
        `;
        
        item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#f9fafb';
        });
        
        item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = 'white';
        });
        
        item.addEventListener('click', () => {
            this.selectCliente(cliente);
        });
        
        return item;
    }
    
    createNewClienteItem(query) {
        const item = document.createElement('div');
        item.className = 'autocomplete-item new-cliente';
        item.style.cssText = `
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.15s;
            border-top: 1px solid #e5e7eb;
        `;
        
        item.innerHTML = `
            <div class="font-medium text-blue-600">
                <i class="fas fa-plus mr-2"></i>
                Criar novo cliente: "${query}"
            </div>
            <div class="text-sm text-gray-500">Será cadastrado automaticamente ao salvar</div>
        `;
        
        item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#eff6ff';
        });
        
        item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = 'white';
        });
        
        item.addEventListener('click', () => {
            this.selectNewCliente(query);
        });
        
        return item;
    }
    
    selectCliente(cliente) {
        // Verificar se o cliente já foi selecionado
        if (this.selectedClientes.includes(cliente.id.toString())) {
            this.input.value = '';
            this.hideDropdown();
            return;
        }
        
        this.selectedClientes.push(cliente.id.toString());
        this.addClienteToContainer(cliente, 'existing');
        
        // Preencher o campo hidden cliente_id
        this.hidden.value = cliente.id;
        
        this.input.value = '';
        this.hideDropdown();
    }
    
    selectNewCliente(nome) {
        // Verificar se já existe um cliente novo com o mesmo nome
        const existingNew = this.selectedClientes.find(id => id.startsWith('new:' + nome));
        if (existingNew) {
            this.input.value = '';
            this.hideDropdown();
            return;
        }
        
        const newCliente = { id: 'new:' + nome, nome: nome, isNew: true };
        this.selectedClientes.push('new:' + nome);
        this.addClienteToContainer(newCliente, 'new');
        
        // Preencher o campo hidden cliente_id com o valor para novo cliente
        this.hidden.value = 'new:' + nome;
        
        this.input.value = '';
        this.hideDropdown();
    }

    addClienteToContainer(cliente, type) {
        if (!this.container) return;
        
        const clienteCard = document.createElement('div');
        
        // Calcular a posição atual para alternância de cores
        const currentPosition = this.container.children.length;
        const isEven = currentPosition % 2 === 0;
        
        // Aplicar alternância de cores
        const backgroundClass = isEven ? 'bg-gray-50 dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-700';
        const textClass = 'text-gray-900 dark:text-white';
        
        // Usar largura total para melhor exibição do nome do cliente
        clienteCard.className = `relative ${backgroundClass} border border-gray-200 dark:border-gray-600 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow w-full col-span-full`;
        clienteCard.setAttribute('data-cliente-id', cliente.id);
        
        const bgColor = type === 'existing' ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400' : 'bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400';
        const label = type === 'existing' ? 'Existente' : 'Novo';
        const inputValue = type === 'existing' ? cliente.id : 'new:' + cliente.nome;
        
        clienteCard.innerHTML = `
            <input type="checkbox" name="clientes[]" value="${inputValue}" checked class="hidden">
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 flex-1 min-w-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                        ${cliente.nome.substring(0, 2).toUpperCase()}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-medium ${textClass} break-words">${cliente.nome}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium ${bgColor} mt-1">
                            ${label}
                        </span>
                    </div>
                </div>
                
                <button type="button" class="ml-4 text-gray-400 hover:text-red-500 remove-cliente flex-shrink-0" data-cliente-id="${cliente.id}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        this.container.appendChild(clienteCard);
        
        // Adicionar event listener para remoção
        const removeBtn = clienteCard.querySelector('.remove-cliente');
        removeBtn.addEventListener('click', () => {
            this.removeCliente(cliente.id);
        });
        
        // Atualizar o layout do container para acomodar melhor o card
        this.updateContainerLayout();
    }

    removeCliente(clienteId) {
        const clienteCard = document.querySelector(`[data-cliente-id="${clienteId}"]`);
        if (clienteCard) {
            clienteCard.remove();
            this.selectedClientes = this.selectedClientes.filter(id => id !== clienteId.toString());
            
            // Limpar o campo hidden se não há mais clientes selecionados
            if (this.selectedClientes.length === 0) {
                this.hidden.value = '';
            } else {
                // Se ainda há clientes, usar o primeiro da lista
                this.hidden.value = this.selectedClientes[0];
            }
            
            // Atualizar layout e cores após remoção
            this.updateContainerLayout();
            this.updateAlternatingColors();
        }
    }

    updateContainerLayout() {
        if (!this.container) return;
        
        const clienteCount = this.container.children.length;
        
        // Ajustar o layout do container baseado no número de clientes
        if (clienteCount === 0) {
            this.container.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4';
        } else if (clienteCount === 1) {
            // Para um cliente, usar largura total
            this.container.className = 'grid grid-cols-1 gap-3 mb-4';
        } else {
            // Para múltiplos clientes, usar grid responsivo
            this.container.className = 'grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4';
        }
    }

    updateAlternatingColors() {
        if (!this.container) return;
        
        const clienteCards = this.container.children;
        
        for (let i = 0; i < clienteCards.length; i++) {
            const card = clienteCards[i];
            const isEven = i % 2 === 0;
            
            // Remover classes de cor antigas
            card.classList.remove('bg-gray-50', 'dark:bg-gray-800', 'bg-gray-100', 'dark:bg-gray-700');
            
            // Aplicar novas classes baseadas na posição atual
            if (isEven) {
                card.classList.add('bg-gray-50', 'dark:bg-gray-800');
            } else {
                card.classList.add('bg-gray-100', 'dark:bg-gray-700');
            }
        }
    }


    
    handleEnter() {
        const items = this.dropdown.querySelectorAll('.autocomplete-item');
        if (items.length > 0) {
            // Se há apenas um item ou o primeiro é um cliente existente, seleciona ele
            const firstItem = items[0];
            if (!firstItem.classList.contains('new-cliente')) {
                firstItem.click();
            } else {
                // Se o primeiro item é "criar novo", cria com o valor atual do input
                this.selectNewCliente(this.input.value.trim());
            }
        } else if (this.input.value.trim()) {
            // Se não há resultados mas há texto, cria novo cliente
            this.selectNewCliente(this.input.value.trim());
        }
    }
    
    showDropdown() {
        this.dropdown.style.display = 'block';
    }
    
    hideDropdown() {
        this.dropdown.style.display = 'none';
    }
    
    reset() {
        this.selectedClientes = [];
        this.hidden.value = '';
        this.input.value = '';
        
        if (this.container) {
            this.container.innerHTML = '';
        }
        
        this.hideDropdown();
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    const clienteInput = document.getElementById('cliente_autocomplete');
    const clienteHidden = document.getElementById('cliente_id');
    
    if (clienteInput && clienteHidden) {
        new ClienteAutocomplete(clienteInput, clienteHidden);
    }
});