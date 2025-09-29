@extends('layouts.app')

@section('title', 'Upload de Assets')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900">
    <!-- Navigation Tags -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-2 py-3">
                <a href="{{ route('assets.index') }}" 
                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Assets
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                    <i class="fas fa-upload mr-1"></i>
                    Upload
                </span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('assets.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Upload de Assets</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Adicione novos assets à sua biblioteca de forma segura</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Upload Form -->
        <form id="upload-form" action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Drag & Drop Area -->
            <div id="drop-zone" 
                 class="relative group min-h-[calc(100vh-200px)] rounded-3xl overflow-hidden transition-all duration-300  bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
                
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-30">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(59, 130, 246, 0.3) 1px, transparent 0); background-size: 20px 20px;"></div>
                </div>
                
                <!-- Default State -->
                <div id="drop-zone-default" class="relative z-10 flex flex-col items-center justify-center h-full min-h-[calc(100vh-200px)] p-12">
                    <!-- Large Upload Icon -->
                    <div class="mb-8 relative">
                        <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-8">
                            <i class="fas fa-cloud-upload-alt text-8xl text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Main Heading -->
                    <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-4 text-center">
                        Arraste e solte seus arquivos aqui
                    </h2>
                    
                    <!-- Subtitle -->
                    <p class="text-xl text-gray-500 dark:text-gray-400 mb-8 text-center max-w-md">
                        Compartilhe seus arquivos de forma rápida e segura
                    </p>
                    
                    <!-- Divider -->
                    <div class="flex items-center mb-8 w-full max-w-xs">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        <span class="px-4 text-gray-400 dark:text-gray-500 font-medium">ou</span>
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    </div>
                    
                    <!-- Select Files Button -->
                    <button type="button" 
                            onclick="document.getElementById('file-input').click()" 
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-12 py-4 rounded-2xl font-semibold text-lg transition-all duration-200 shadow-lg">
                        <span class="flex items-center">
                            <i class="fas fa-folder-open mr-3 text-xl"></i>
                            Selecionar Arquivos
                        </span>
                    </button>
                    
                    <!-- File Size Info -->
                    <div class="mt-8 flex items-center space-x-6 text-sm text-gray-400 dark:text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                            <span>100% Seguro</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-infinity mr-2 text-blue-500"></i>
                            <span>Sem limites</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-purple-500"></i>
                            <span>Máx: 10MB por arquivo</span>
                        </div>
                    </div>
                    
                    <!-- Drag Indicator -->
                    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <div class="flex items-center space-x-2 text-blue-500">
                            <i class="fas fa-mouse-pointer"></i>
                            <span class="text-sm font-medium">Solte os arquivos aqui</span>
                        </div>
                    </div>
                </div>

                <!-- Drag Over State -->
                <div id="drop-zone-dragover" class="hidden relative z-10 flex flex-col items-center justify-center h-full min-h-[calc(100vh-200px)] p-12">
                    <div class="mb-8 relative">
                        <div class="relative bg-gradient-to-r from-green-500 to-emerald-600 rounded-full p-8 animate-pulse">
                            <i class="fas fa-download text-8xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent text-center">
                        Solte os arquivos para fazer upload
                    </h2>
                </div>

                <!-- File Input -->
                <input type="file" 
                       id="file-input" 
                       name="files[]" 
                       multiple 
                       accept="image/*,.ttf,.otf,.woff,.woff2" 
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
            </div>

            <!-- Hidden form fields for upload options -->
            <input type="text" id="tags" name="tags" style="display: none;">
            <input type="checkbox" id="auto-organize" name="auto_organize" value="1" checked style="display: none;">
            <input type="checkbox" id="generate-thumbnails" name="generate_thumbnails" value="1" checked style="display: none;">
            
            <!-- Hidden submit button -->
            <button type="submit" id="start-upload" style="display: none;">Upload</button>

        </form>
        
        <!-- Selected Files Section -->
        <div id="selected-files" class="hidden mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-file-alt mr-3 text-blue-500"></i>
                    Arquivos Selecionados
                </h3>
                <div id="files-list" class="space-y-4">
                    <!-- Files will be added here dynamically -->
                </div>
                
                <!-- Upload Options -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tags -->
                        <div>
                            <label for="tags-visible" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-tags mr-2 text-blue-500"></i>
                                Tags (opcional)
                            </label>
                            <input type="text" 
                                   id="tags-visible" 
                                   placeholder="Adicione tags separadas por vírgula"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-all duration-200">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Ex: logo, ícone, fonte, banner
                            </p>
                        </div>
                        
                        <!-- Options -->
                        <div class="space-y-4">
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <input type="checkbox" 
                                       id="auto-organize-visible" 
                                       checked
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="auto-organize-visible" class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-sort-alpha-down mr-2 text-green-500"></i>
                                    Organizar automaticamente por tipo
                                </label>
                            </div>
                            
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <input type="checkbox" 
                                       id="generate-thumbnails-visible" 
                                       checked
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="generate-thumbnails-visible" class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-image mr-2 text-purple-500"></i>
                                    Gerar miniaturas automaticamente
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <button type="button" 
                                id="cancel-upload" 
                                class="px-8 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>
                        <button type="button" 
                                id="start-upload-visible" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition-all duration-200 font-medium shadow-lg">
                            <i class="fas fa-upload mr-2"></i>
                            Fazer Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Progress -->
        <div id="upload-progress" class="mt-8 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Fazendo Upload...</h3>
                    <span id="progress-percentage" class="text-sm text-gray-600 dark:text-gray-400">0%</span>
                </div>
                
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
                    <div id="progress-bar" 
                         class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                         style="width: 0%"></div>
                </div>
                
                <div id="upload-status" class="text-sm text-gray-600 dark:text-gray-400">
                    Preparando upload...
                </div>
            </div>
        </div>

        <!-- Upload Results -->
        <div id="upload-results" class="hidden mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8">
                <!-- Success Message -->
                <div id="success-message" class="hidden text-center">
                    <div class="mb-6">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-check text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-2">
                            Upload Concluído!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Seus arquivos foram enviados com sucesso.
                        </p>
                    </div>
                    
                    <div id="uploaded-files-list" class="space-y-3 mb-8 max-w-md mx-auto">
                        <!-- Uploaded files will be listed here -->
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <button type="button" 
                                onclick="resetUpload()" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition-all duration-200 font-medium shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Fazer Novo Upload
                        </button>
                        <a href="{{ route('assets.index') }}" 
                           class="inline-flex items-center justify-center px-8 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Assets
                        </a>
                    </div>
                </div>
                
                <!-- Error Message -->
                <div id="error-message" class="hidden text-center">
                    <div class="mb-6">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-2">
                            Erro no Upload
                        </h3>
                        <p id="error-text" class="text-gray-600 dark:text-gray-400 mb-6">
                            Ocorreu um erro durante o upload.
                        </p>
                    </div>
                    
                    <button type="button" 
                            onclick="resetUpload()" 
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition-all duration-200 font-medium shadow-lg">
                        <i class="fas fa-redo mr-2"></i>
                        Tentar Novamente
                    </button>
                </div>
                
                <div id="success-files" class="hidden">
                    <div class="mb-4">
                        <h4 class="text-md font-medium text-green-600 dark:text-green-400 mb-2">
                            <i class="fas fa-check-circle mr-2"></i>
                            Arquivos enviados com sucesso
                        </h4>
                        <div id="success-list" class="space-y-2"></div>
                    </div>
                </div>
                
                <div id="error-files" class="hidden">
                    <div class="mb-4">
                        <h4 class="text-md font-medium text-red-600 dark:text-red-400 mb-2">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Arquivos com erro
                        </h4>
                        <div id="error-list" class="space-y-2"></div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-6">
                    <button type="button" 
                            onclick="resetUpload()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <i class="fas fa-redo mr-2"></i>
                        Fazer Novo Upload
                    </button>
                    
                    <a href="{{ route('assets.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Ver Assets
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="mt-12">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-2">
                    Dicas para um melhor upload
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Maximize a qualidade e organização dos seus assets
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-image text-white text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Imagens</h4>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Use formatos JPG, PNG, SVG ou GIF. Recomendamos resolução mínima de 300x300px para melhor qualidade.
                    </p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-font text-white text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Fontes</h4>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Suportamos TTF, OTF, WOFF e WOFF2. Fontes web (WOFF/WOFF2) são recomendadas para melhor performance.
                    </p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-tags text-white text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Organização</h4>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Use tags descritivas e organize por categorias. Isso facilita a busca e o gerenciamento dos seus assets.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/asset-library.js') }}"></script>
<script>
let selectedFiles = [];
let isUploading = false;

// DOM Elements
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');
const dropZoneDefault = document.getElementById('drop-zone-default');
const dropZoneDragover = document.getElementById('drop-zone-dragover');
const selectedFilesDiv = document.getElementById('selected-files');
const uploadProgress = document.getElementById('upload-progress');
const uploadResults = document.getElementById('upload-results');
const uploadForm = document.getElementById('upload-form');

// Drag and Drop Events
dropZone.addEventListener('dragover', handleDragOver);
dropZone.addEventListener('dragleave', handleDragLeave);
dropZone.addEventListener('drop', handleDrop);

// File Input Change
fileInput.addEventListener('change', handleFileSelect);

// Form Submit
uploadForm.addEventListener('submit', handleFormSubmit);

// Test Redirect Button
const testRedirectBtn = document.getElementById('test-redirect-btn');
if (testRedirectBtn) {
    testRedirectBtn.addEventListener('click', function() {
        console.log('Testing redirect to /assets...');
        window.location.href = '/assets';
    });
}

// Cancel Upload Button
const cancelUploadBtn = document.getElementById('cancel-upload');
if (cancelUploadBtn) {
    cancelUploadBtn.addEventListener('click', function() {
        clearFiles();
    });
}

// Visible upload button
const startUploadVisibleBtn = document.getElementById('start-upload-visible');
if (startUploadVisibleBtn) {
    startUploadVisibleBtn.addEventListener('click', function() {
        // Sync visible fields with hidden form fields
        document.getElementById('tags').value = document.getElementById('tags-visible').value;
        document.getElementById('auto-organize').checked = document.getElementById('auto-organize-visible').checked;
        document.getElementById('generate-thumbnails').checked = document.getElementById('generate-thumbnails-visible').checked;
        
        // Trigger form submission
        document.getElementById('start-upload').click();
    });
}

function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    
    dropZoneDefault.classList.add('hidden');
    dropZoneDragover.classList.remove('hidden');
    dropZone.classList.add('border-green-400', 'bg-green-50', 'dark:bg-green-900/20');
    dropZone.classList.remove('border-gray-300', 'dark:border-gray-600');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    
    // Only hide if we're leaving the drop zone entirely
    if (!dropZone.contains(e.relatedTarget)) {
        resetDropZone();
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    
    resetDropZone();
    
    const files = Array.from(e.dataTransfer.files);
    processFiles(files);
}

function handleFileSelect(e) {
    const files = Array.from(e.target.files);
    processFiles(files);
}

function resetDropZone() {
    dropZoneDefault.classList.remove('hidden');
    dropZoneDragover.classList.add('hidden');
    dropZone.classList.remove('border-green-400', 'bg-green-50', 'dark:bg-green-900/20');
    dropZone.classList.add('border-gray-300', 'dark:border-gray-600');
}

function processFiles(files) {
    const validFiles = [];
    const invalidFiles = [];
    
    const allowedTypes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml',
        'font/ttf', 'font/otf', 'font/woff', 'font/woff2',
        'application/font-woff', 'application/font-woff2',
        'application/x-font-ttf', 'application/x-font-otf'
    ];
    
    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.ttf', '.otf', '.woff', '.woff2'];
    
    files.forEach(file => {
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        const isValidType = allowedTypes.includes(file.type) || allowedExtensions.includes(fileExtension);
        const isValidSize = file.size <= 10 * 1024 * 1024; // 10MB
        
        if (isValidType && isValidSize) {
            validFiles.push(file);
        } else {
            invalidFiles.push({
                file: file,
                reason: !isValidType ? 'Formato não suportado' : 'Arquivo muito grande (máx. 10MB)'
            });
        }
    });
    
    if (invalidFiles.length > 0) {
        showInvalidFilesAlert(invalidFiles);
    }
    
    if (validFiles.length > 0) {
        selectedFiles = [...selectedFiles, ...validFiles];
        updateFilesList();
        showUploadOptions();
    }
}

function showInvalidFilesAlert(invalidFiles) {
    const messages = invalidFiles.map(item => `${item.file.name}: ${item.reason}`);
    showError('Alguns arquivos não puderam ser adicionados:\n\n' + messages.join('\n'));
}

function showError(message) {
    // Create a temporary error notification
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
    
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

function updateFilesList() {
    const filesList = document.getElementById('files-list');
    filesList.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const fileItem = createFileItem(file, index);
        filesList.appendChild(fileItem);
    });
    
    selectedFilesDiv.classList.toggle('hidden', selectedFiles.length === 0);
}

function createFileItem(file, index) {
    const div = document.createElement('div');
    div.className = 'flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600';
    
    const fileType = getFileType(file);
    const fileIcon = getFileIcon(fileType);
    const fileSize = formatFileSize(file.size);
    
    div.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r ${getFileIconGradient(fileType)} rounded-lg flex items-center justify-center">
                <i class="${fileIcon} text-white"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">${file.name}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">${fileType.toUpperCase()} • ${fileSize}</p>
            </div>
        </div>
        <button type="button" 
                onclick="removeFile(${index})" 
                class="w-8 h-8 flex items-center justify-center text-red-500 hover:text-white hover:bg-red-500 rounded-full transition-all duration-200">
            <i class="fas fa-times text-sm"></i>
        </button>
    `;
    
    return div;
}

function getFileIconGradient(fileType) {
    switch (fileType) {
        case 'image':
            return 'from-blue-500 to-cyan-500';
        case 'font':
            return 'from-purple-500 to-pink-500';
        default:
            return 'from-gray-500 to-gray-600';
    }
}

function getFileType(file) {
    const extension = file.name.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(extension)) {
        return 'image';
    } else if (['ttf', 'otf', 'woff', 'woff2'].includes(extension)) {
        return 'font';
    }
    
    return 'unknown';
}

function getFileIcon(fileType) {
    switch (fileType) {
        case 'image':
            return 'fas fa-image';
        case 'font':
            return 'fas fa-font';
        default:
            return 'fas fa-file';
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFilesList();
    
    if (selectedFiles.length === 0) {
        hideUploadOptions();
    }
}

function clearFiles() {
    selectedFiles = [];
    fileInput.value = '';
    updateFilesList();
    hideUploadOptions();
}

function hideUploadOptions() {
    selectedFilesDiv.classList.add('hidden');
}

function showUploadOptions() {
    // The upload options are part of the selected-files section
    // So we just need to ensure the selected files section is visible
    if (selectedFilesDiv) {
        selectedFilesDiv.classList.remove('hidden');
    }
}

function hideUploadOptions() {
    // Hide the selected files section which contains the upload options
    if (selectedFilesDiv) {
        selectedFilesDiv.classList.add('hidden');
    }
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    if (selectedFiles.length === 0 || isUploading) {
        return;
    }
    
    isUploading = true;
    showUploadProgress();
    
    const formData = new FormData();
    
    // Add files
    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });
    
    // Add other form data
    const tags = document.getElementById('tags').value;
    const autoOrganize = document.getElementById('auto-organize').checked;
    const generateThumbnails = document.getElementById('generate-thumbnails').checked;
    
    if (tags) formData.append('tags', tags);
    if (autoOrganize) formData.append('auto_organize', '1');
    if (generateThumbnails) formData.append('generate_thumbnails', '1');
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);
    
    // Upload with progress
    const xhr = new XMLHttpRequest();
    
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            updateProgress(percentComplete, 'Enviando arquivos...');
        }
    });
    
    xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                console.log('Upload successful, response:', response);
                
                // Mostrar resultados brevemente
                showUploadResults(response);
                
                // Redirecionar após 2 segundos para o usuário ver o resultado
                setTimeout(function() {
                    console.log('Redirecting to /assets...');
                    window.location.href = '/assets';
                }, 2000);
                
            } catch (error) {
                console.error('Error parsing response:', error);
                showUploadError('Erro ao processar resposta do servidor');
            }
        } else {
            showUploadError('Erro no upload: ' + xhr.statusText);
        }
        isUploading = false;
    });
    
    xhr.addEventListener('error', function() {
        showUploadError('Erro de conexão durante o upload');
        isUploading = false;
    });
    
    xhr.open('POST', uploadForm.action);
    xhr.send(formData);
}

function showUploadProgress() {
    if (selectedFilesDiv) {
        selectedFilesDiv.classList.add('hidden');
    }
    uploadProgress.classList.remove('hidden');
    updateProgress(0, 'Preparando upload...');
}

function updateProgress(percentage, status) {
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const uploadStatus = document.getElementById('upload-status');
    
    progressBar.style.width = percentage + '%';
    progressPercentage.textContent = Math.round(percentage) + '%';
    uploadStatus.textContent = status;
}

function showUploadResults(response) {
    uploadProgress.classList.add('hidden');
    uploadResults.classList.remove('hidden');
    
    const successFiles = document.getElementById('success-files');
    const errorFiles = document.getElementById('error-files');
    const successList = document.getElementById('success-list');
    const errorList = document.getElementById('error-list');
    
    // Clear previous results
    successList.innerHTML = '';
    errorList.innerHTML = '';
    successFiles.classList.add('hidden');
    errorFiles.classList.add('hidden');
    
    // Show successful uploads
    if (response.success && response.assets && response.assets.length > 0) {
        successFiles.classList.remove('hidden');
        
        response.assets.forEach(asset => {
            const item = document.createElement('div');
            item.className = 'flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg';
            item.innerHTML = `
                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                <span class="text-sm text-gray-900 dark:text-white">${asset.original_name}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">${asset.formatted_size}</span>
            `;
            successList.appendChild(item);
        });
    }
    
    // Show errors
    if (response.errors && response.errors.length > 0) {
        errorFiles.classList.remove('hidden');
        
        response.errors.forEach(error => {
            const item = document.createElement('div');
            item.className = 'flex items-center space-x-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg';
            item.innerHTML = `
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
                <div class="flex-1">
                    <span class="text-sm text-gray-900 dark:text-white">${error.file || 'Arquivo desconhecido'}</span>
                    <p class="text-xs text-red-600 dark:text-red-400">${error.message}</p>
                </div>
            `;
            errorList.appendChild(item);
        });
    }
}

function showUploadError(message) {
    uploadProgress.classList.add('hidden');
    uploadResults.classList.remove('hidden');
    
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    const successMessage = document.getElementById('success-message');
    
    // Hide success message and show error message
    successMessage.classList.add('hidden');
    errorMessage.classList.remove('hidden');
    
    // Update error text
    if (errorText) {
        errorText.textContent = message;
    }
    
    // Also update the error files section if it exists
    const errorFiles = document.getElementById('error-files');
    const errorList = document.getElementById('error-list');
    
    if (errorFiles && errorList) {
        errorList.innerHTML = '';
        errorFiles.classList.remove('hidden');
        
        const item = document.createElement('div');
        item.className = 'flex items-center space-x-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg';
        item.innerHTML = `
            <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
            <span class="text-sm text-red-600 dark:text-red-400">${message}</span>
        `;
        errorList.appendChild(item);
    }
}

function resetUpload() {
    selectedFiles = [];
    fileInput.value = '';
    isUploading = false;
    
    // Reset form
    document.getElementById('tags').value = '';
    document.getElementById('auto-organize').checked = true;
    document.getElementById('generate-thumbnails').checked = true;
    
    // Hide sections
    selectedFilesDiv.classList.add('hidden');
    uploadProgress.classList.add('hidden');
    uploadResults.classList.add('hidden');
    
    // Reset file list
    updateFilesList();
}

// Prevent default drag behaviors on the entire page
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    document.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Any initialization code here
});
</script>
@endpush