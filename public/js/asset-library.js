/**
 * Asset Library JavaScript
 * Handles upload, preview, download and management functionality
 */

class AssetLibrary {
    constructor() {
        this.selectedAssets = new Set();
        this.uploadQueue = [];
        this.isUploading = false;
        this.init();
    }

    init() {
        this.initUploadArea();
        this.initAssetSelection();
        this.initBatchDownload();
        this.initSearch();
        this.initFilters();
        this.initModals();
        this.initFontPreview();
    }

    // Upload functionality
    initUploadArea() {
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        const uploadForm = document.getElementById('upload-form');

        if (!uploadArea || !fileInput) return;

        // Drag and drop events
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            if (!uploadArea.contains(e.relatedTarget)) {
                uploadArea.classList.remove('drag-over');
            }
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            const files = Array.from(e.dataTransfer.files);
            this.handleFileSelection(files);
        });

        // Click to select files
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            this.handleFileSelection(files);
        });

        // Form submission
        if (uploadForm) {
            uploadForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.uploadFiles();
            });
        }
    }

    handleFileSelection(files) {
        const validFiles = files.filter(file => this.validateFile(file));
        this.uploadQueue = [...this.uploadQueue, ...validFiles];
        this.displaySelectedFiles();
    }

    validateFile(file) {
        const allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'font/ttf', 'font/otf', 'font/woff', 'font/woff2',
            'application/font-woff', 'application/font-woff2',
            'application/x-font-ttf', 'application/x-font-otf',
            'application/font-sfnt', 'application/vnd.ms-fontobject',
            'font/opentype', 'application/octet-stream'
        ];
        
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            this.showError(`Tipo de arquivo nao suportado: ${file.name}`);
            return false;
        }

        if (file.size > maxSize) {
            this.showError(`Arquivo muito grande: ${file.name}`);
            return false;
        }

        return true;
    }

    displaySelectedFiles() {
        const container = document.getElementById('selected-files');
        if (!container) return;

        container.innerHTML = '';
        
        this.uploadQueue.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'bg-white p-4 rounded-lg border border-gray-200 flex items-center justify-between';
            
            fileItem.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas ${this.getFileIcon(file.type)} text-gray-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">${file.name}</p>
                        <p class="text-sm text-gray-500">${this.formatFileSize(file.size)}</p>
                    </div>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700" onclick="assetLibrary.removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(fileItem);
        });

        // Show/hide upload section
        const uploadSection = document.getElementById('upload-section');
        if (uploadSection) {
            uploadSection.style.display = this.uploadQueue.length > 0 ? 'block' : 'none';
        }
    }

    removeFile(index) {
        this.uploadQueue.splice(index, 1);
        this.displaySelectedFiles();
    }

    async checkAuthentication() {
        try {
            const response = await fetch('/api/auth/check');
            if (response.ok) {
                const data = await response.json();
                return data.authenticated;
            }
            return false;
        } catch (error) {
            console.error('Erro ao verificar autenticacao:', error);
            return false;
        }
    }

    async uploadFiles() {
        if (this.uploadQueue.length === 0 || this.isUploading) return;

        this.isUploading = true;
        const formData = new FormData();
        const progressContainer = document.getElementById('upload-progress');

        // Add files to form data
        this.uploadQueue.forEach(file => {
            formData.append('files[]', file);
        });

        // Add other form data
        const tags = document.getElementById('upload-tags')?.value || '';
        const autoOrganize = document.getElementById('auto-organize')?.checked || false;
        const generateThumbnails = document.getElementById('generate-thumbnails')?.checked || true;

        formData.append('tags', tags);
        formData.append('auto_organize', autoOrganize);
        formData.append('generate_thumbnails', generateThumbnails);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        // Show progress
        if (progressContainer) {
            progressContainer.style.display = 'block';
            progressContainer.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <span class="text-blue-800">Fazendo upload dos arquivos...</span>
                    </div>
                    <div class="mt-3">
                        <div class="bg-blue-200 rounded-full h-2">
                            <div id="upload-progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            `;
        }

        try {
            console.log('Starting simplified upload...');
            console.log('Current URL:', window.location.href);
            
            // Usar fetch simples em vez de XMLHttpRequest
            const response = await fetch('/assets/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            console.log('Upload response status:', response.status);
            console.log('Upload response ok:', response.ok);
            
            if (response.ok) {
                console.log('Upload successful! Processing response...');
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                // Processar resposta JSON - leitura única do stream
                let responseData;
                try {
                    const responseText = await response.text();
                    console.log('Raw response text:', responseText);
                    responseData = responseText ? JSON.parse(responseText) : { success: true };
                    console.log('Parsed response data:', responseData);
                } catch (e) {
                    console.warn('Could not parse JSON response:', e);
                    responseData = { success: true };
                }
                
                // Limpar fila de upload
                console.log('Clearing upload queue...');
                this.clearUploadQueue();
                console.log('Upload queue cleared');
                
                // Implementar redirecionamento ROBUSTO com multiplas estrategias
                console.log('Starting robust redirect process...');
                this.performRobustRedirect();
                
                return;
            } else {
                // Clonar a resposta para ler o corpo com segurança em caso de erro
                const responseClone = response.clone();
                let errorMessage;

                try {
                    // Tentar ler como JSON primeiro (para erros estruturados da API)
                    const errorData = await response.json();
                    errorMessage = errorData.message || `Erro ${response.status}`;
                } catch (jsonError) {
                    // Se falhar, tentar ler como texto (pode ser um erro de servidor, HTML, etc.)
                    try {
                        const errorText = await responseClone.text();
                        // Tenta extrair uma mensagem mais limpa de um possível HTML de erro
                        const match = errorText.match(/<title>(.*?)<\/title>/i);
                        if (match && match[1]) {
                            errorMessage = match[1];
                        } else {
                            errorMessage = errorText.substring(0, 100); // Limita a mensagem para não poluir
                        }
                    } catch (textError) {
                        // Fallback final se nenhuma leitura do corpo for bem-sucedida
                        errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                    }
                }
                throw new Error(errorMessage);
            }
            
        } catch (error) {
            console.error('Upload error:', error);
            this.showError('Erro no upload: ' + error.message);
        } finally {
            this.isUploading = false;
            if (progressContainer) {
                progressContainer.style.display = 'none';
            }
        }
    }

    // Metodo removido - usando fetch simples agora

    // Metodos removidos - redirecionamento agora e imediato apos upload bem-sucedido

    clearUploadQueue() {
        this.uploadQueue = [];
        this.isUploading = false;
        
        // Limpar a exibicao de arquivos selecionados
        const selectedFilesContainer = document.getElementById('selected-files');
        if (selectedFilesContainer) {
            selectedFilesContainer.innerHTML = '';
        }
        
        // Resetar o input de arquivo
        const fileInput = document.getElementById('file-input');
        if (fileInput) {
            fileInput.value = '';
        }
        
        // Esconder o container de progresso
        const progressContainer = document.getElementById('upload-progress');
        if (progressContainer) {
            progressContainer.style.display = 'none';
        }
    }

    // Metodo robusto de redirecionamento com multiplas estrategias
    performRobustRedirect() {
        const targetUrl = '/assets';
        console.log('Target URL:', targetUrl);
        console.log('Current URL:', window.location.href);
        
        // Estrategia 1: window.location.href (mais compativel)
        console.log('Attempting redirect strategy 1: window.location.href');
        try {
            window.location.href = targetUrl;
            console.log('Strategy 1 executed');
        } catch (e) {
            console.error('Strategy 1 failed:', e);
        }
        
        // Estrategia 2: Timeout forcado com window.location.replace
        setTimeout(() => {
            console.log('Executing fallback strategy 2: window.location.replace');
            try {
                if (window.location.pathname === '/assets/upload') {
                    console.log('Still on upload page, forcing redirect...');
                    window.location.replace(targetUrl);
                    console.log('Strategy 2 executed');
                }
            } catch (e) {
                console.error('Strategy 2 failed:', e);
            }
        }, 500);
        
        // Estrategia 3: Timeout forcado final com document.location
        setTimeout(() => {
            console.log('Executing final strategy 3: document.location');
            try {
                if (window.location.pathname === '/assets/upload') {
                    console.log('FORCING FINAL REDIRECT...');
                    document.location = targetUrl;
                    console.log('Strategy 3 executed');
                }
            } catch (e) {
                console.error('Strategy 3 failed:', e);
                // Ultimo recurso: recarregar a pagina com nova URL
                console.log('Last resort: reloading with new URL');
                window.location.reload();
            }
        }, 1500);
        
        // Log de monitoramento
        setTimeout(() => {
            console.log('Redirect status check:');
            console.log('Current URL after 3s:', window.location.href);
            console.log('Pathname:', window.location.pathname);
            if (window.location.pathname === '/assets/upload') {
                console.error('REDIRECT FAILED - Still on upload page!');
            } else {
                console.log('REDIRECT SUCCESSFUL!');
            }
        }, 3000);
    }

    // Asset selection functionality
    initAssetSelection() {
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('asset-checkbox')) {
                const assetId = e.target.value;
                if (e.target.checked) {
                    this.selectedAssets.add(assetId);
                } else {
                    this.selectedAssets.delete(assetId);
                }
                this.updateBatchActions();
            }
        });

        // Select all functionality
        const selectAllBtn = document.getElementById('select-all');
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', () => {
                const checkboxes = document.querySelectorAll('.asset-checkbox');
                const allSelected = this.selectedAssets.size === checkboxes.length;
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = !allSelected;
                    if (!allSelected) {
                        this.selectedAssets.add(checkbox.value);
                    } else {
                        this.selectedAssets.delete(checkbox.value);
                    }
                });
                
                if (allSelected) {
                    this.selectedAssets.clear();
                }
                
                this.updateBatchActions();
            });
        }
    }

    updateBatchActions() {
        const batchActions = document.getElementById('batch-actions');
        const selectedCount = document.getElementById('selected-count');
        
        if (batchActions) {
            batchActions.style.display = this.selectedAssets.size > 0 ? 'block' : 'none';
        }
        
        if (selectedCount) {
            selectedCount.textContent = this.selectedAssets.size;
        }
    }

    // Batch download functionality
    initBatchDownload() {
        const batchDownloadBtn = document.getElementById('batch-download');
        if (batchDownloadBtn) {
            batchDownloadBtn.addEventListener('click', () => {
                this.downloadBatch();
            });
        }
    }

    async downloadBatch() {
        if (this.selectedAssets.size === 0) {
            this.showError('Nenhum asset selecionado');
            return;
        }

        const assetIds = Array.from(this.selectedAssets);
        
        // Verificar se o token CSRF existe
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            this.showError('Token CSRF nao encontrado');
            return;
        }
        
        try {
            const response = await fetch('/assets/download-batch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ asset_ids: assetIds })
            });

            if (response.ok) {
                const blob = await response.blob();
                
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `assets-${Date.now()}.zip`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                this.showSuccess('Download iniciado!');
            } else {
                // Ler a resposta como texto apenas uma vez
                let errorMessage;
                try {
                    const errorText = await response.text();
                    errorMessage = `HTTP ${response.status}: ${errorText}`;
                } catch (parseError) {
                    errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                }
                throw new Error(errorMessage);
            }
        } catch (error) {
            this.showError('Erro no download: ' + error.message);
        }
    }

    // Search functionality
    initSearch() {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.performSearch(e.target.value);
                }, 300);
            });
        }
    }

    async performSearch(query) {
        const resultsContainer = document.getElementById('assets-grid');
        if (!resultsContainer) return;

        try {
            const response = await fetch(`/api/assets/search?q=${encodeURIComponent(query)}`);
            const assets = await response.json();
            
            this.renderAssets(assets, resultsContainer);
        } catch (error) {
            this.showError('Erro na busca: ' + error.message);
        }
    }

    // Filter functionality
    initFilters() {
        const filterInputs = document.querySelectorAll('.filter-input');
        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.applyFilters();
            });
        });
    }

    async applyFilters() {
        const filters = {};
        const filterInputs = document.querySelectorAll('.filter-input');
        
        filterInputs.forEach(input => {
            if (input.value) {
                filters[input.name] = input.value;
            }
        });

        const queryString = new URLSearchParams(filters).toString();
        
        try {
            const response = await fetch(`/assets/images?${queryString}`);
            const html = await response.text();
            
            // Update the page content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.getElementById('assets-grid');
            const currentGrid = document.getElementById('assets-grid');
            
            if (newGrid && currentGrid) {
                currentGrid.innerHTML = newGrid.innerHTML;
            }
        } catch (error) {
            this.showError('Erro ao aplicar filtros: ' + error.message);
        }
    }

    // Modal functionality
    initModals() {
        // Image modal
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('view-image')) {
                e.preventDefault();
                const imageUrl = e.target.dataset.image;
                const imageName = e.target.dataset.name;
                this.showImageModal(imageUrl, imageName);
            }
            
            if (e.target.classList.contains('test-font')) {
                e.preventDefault();
                const fontUrl = e.target.dataset.font;
                const fontName = e.target.dataset.name;
                this.showFontModal(fontUrl, fontName);
            }
        });

        // Close modal functionality
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop') || e.target.classList.contains('close-modal')) {
                this.closeModal();
            }
        });

        // ESC key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }

    showImageModal(imageUrl, imageName) {
        const modal = document.getElementById('image-modal');
        if (!modal) return;

        const modalImage = modal.querySelector('#modal-image');
        const modalTitle = modal.querySelector('#modal-title');
        
        if (modalImage) modalImage.src = imageUrl;
        if (modalTitle) modalTitle.textContent = imageName;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    showFontModal(fontUrl, fontName) {
        const modal = document.getElementById('font-modal');
        if (!modal) return;

        const modalTitle = modal.querySelector('#font-modal-title');
        if (modalTitle) modalTitle.textContent = fontName;
        
        // Load font
        this.loadFont(fontUrl, fontName);
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    closeModal() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }

    // Font preview functionality
    initFontPreview() {
        const fontControls = document.querySelectorAll('.font-control');
        fontControls.forEach(control => {
            control.addEventListener('input', () => {
                this.updateFontPreview();
            });
        });
    }

    async loadFont(fontUrl, fontName) {
        try {
            const font = new FontFace(fontName, `url(${fontUrl})`);
            await font.load();
            document.fonts.add(font);
            
            // Update preview
            const preview = document.getElementById('font-preview');
            if (preview) {
                preview.style.fontFamily = fontName;
            }
        } catch (error) {
            this.showError('Erro ao carregar fonte: ' + error.message);
        }
    }

    updateFontPreview() {
        const preview = document.getElementById('font-preview');
        if (!preview) return;

        const text = document.getElementById('preview-text')?.value || 'Sample Text';
        const size = document.getElementById('font-size')?.value || '24';
        const lineHeight = document.getElementById('line-height')?.value || '1.5';
        const textColor = document.getElementById('text-color')?.value || '#000000';
        const bgColor = document.getElementById('bg-color')?.value || '#ffffff';

        preview.textContent = text;
        preview.style.fontSize = size + 'px';
        preview.style.lineHeight = lineHeight;
        preview.style.color = textColor;
        preview.style.backgroundColor = bgColor;
    }

    // Utility functions
    getFileIcon(type) {
        if (type.startsWith('image/')) return 'fa-image';
        if (type.includes('font')) return 'fa-font';
        return 'fa-file';
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.textContent = message;
        
        const container = document.querySelector('.upload-container') || document.body;
        container.insertBefore(errorDiv, container.firstChild);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    showSuccess(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'alert alert-success';
        successDiv.textContent = message;
        
        const container = document.querySelector('.upload-container') || document.body;
        container.insertBefore(successDiv, container.firstChild);
        
        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'success' ? 'bg-green-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    renderAssets(assets, container) {
        // This method would render assets based on the current page type
        // Implementation depends on the specific page structure
        console.log('Rendering assets:', assets);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.assetLibrary = new AssetLibrary();
});

// Delete asset functionality
function deleteAsset(assetId) {
    if (!confirm('Tem certeza que deseja excluir este asset?')) {
        return;
    }

    fetch(`/assets/${assetId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove asset from DOM
            const assetElement = document.querySelector(`[data-asset-id="${assetId}"]`);
            if (assetElement) {
                assetElement.remove();
            }
            window.assetLibrary.showSuccess('Asset excluido com sucesso!');
        } else {
            window.assetLibrary.showError('Erro ao excluir asset');
        }
    })
    .catch(error => {
        window.assetLibrary.showError('Erro ao excluir asset: ' + error.message);
    });
}

// Download single asset
function downloadAsset(assetId) {
    window.location.href = `/assets/${assetId}/download`;
}