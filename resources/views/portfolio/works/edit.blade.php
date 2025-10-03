@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Trabalho</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $work->title }}</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                @if($work->status === 'published')
                    <a href="{{ route('public.portfolio.public.work', $work->slug) }}" target="_blank"
                       class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                @endif
                
                <form action="{{ route('portfolio.works.destroy', $work) }}" method="POST" class="inline"
                      onsubmit="return confirm('Tem certeza que deseja excluir este trabalho?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center w-10 h-10 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
                
                <a href="{{ route('portfolio.works.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto ">
        <form action="{{ route('portfolio.works.update', $work) }}" method="POST" enctype="multipart/form-data" 
              x-data="workForm()" @submit="submitForm($event)" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Progress Steps -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <nav aria-label="Progress">
                    <ol class="flex items-center justify-center sm:justify-start">
                        <li class="relative" :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
                            <button type="button" @click="setStep(1)" class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 1 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    1
                                </span>
                                <span class="ml-2 text-sm font-medium hidden sm:block">Informações Básicas</span>
                            </button>
                        </li>
                        
                        <li class="relative ml-4 sm:ml-8" :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700" :class="currentStep >= 2 ? 'bg-blue-600' : ''"></div>
                            </div>
                            <button type="button" @click="setStep(2)" class="relative flex items-center bg-white dark:bg-gray-800">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 2 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    2
                                </span>
                                <span class="ml-2 text-sm font-medium hidden sm:block">Imagens</span>
                            </button>
                        </li>
                        
                        <li class="relative ml-4 sm:ml-8" :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700" :class="currentStep >= 3 ? 'bg-blue-600' : ''"></div>
                            </div>
                            <button type="button" @click="setStep(3)" class="relative flex items-center bg-white dark:bg-gray-800">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 3 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    3
                                </span>
                                <span class="ml-2 text-sm font-medium hidden sm:block">SEO & Publicação</span>
                            </button>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <!-- Step 1: Basic Information -->
            <div x-show="currentStep === 1" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informações Básicas</h2>
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $work->title) }}" required
                           x-model="form.title" @input="generateSlug()"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $work->slug) }}"
                           x-model="form.slug"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-300 @enderror">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">URL amigável</p>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição Curta</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror">{{ old('description', $work->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Conteúdo Completo
                    </label>
                    
                    <!-- Editor de Texto Rico -->
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden @error('content') border-red-300 @enderror">
                        <!-- Barra de Ferramentas -->
                        <div class="bg-gray-50 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600 p-2 flex flex-wrap gap-1">
                            <button type="button" onclick="formatText('bold')" class="editor-btn" title="Negrito">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="editor-btn" title="Itálico">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="editor-btn" title="Sublinhado">
                                <i class="fas fa-underline"></i>
                            </button>
                            <button type="button" onclick="formatText('strikeThrough')" class="editor-btn" title="Riscado">
                                <i class="fas fa-strikethrough"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="formatText('insertOrderedList')" class="editor-btn" title="Lista Numerada">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="editor-btn" title="Lista com Marcadores">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="showLinkModal()" class="editor-btn" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="insertLineBreak()" class="editor-btn" title="Quebra de Linha">
                                <i class="fas fa-level-down-alt"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="undoEdit()" class="editor-btn" title="Desfazer">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                        
                        <!-- Editor Content -->
                        <div id="content-editor" 
                             contenteditable="true"
                             class="w-full min-h-[200px] px-3 py-2 border border-t-0 border-gray-300 dark:border-gray-600 rounded-b-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                             style="max-height: 400px; overflow-y: auto;"
                             placeholder="Descreva detalhadamente o trabalho, processo criativo, resultados, etc.">{!! old('content', $work->content) !!}</div>
                        
                        <!-- Hidden input to store the content -->
                         <input type="hidden" id="content" name="content" value="{{ old('content', $work->content) }}">
                     </div>
                     
                     @error('content')
                         <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                 </div>

                 <!-- Modal para inserção de links -->
                 <div id="linkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                     <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 max-w-md mx-4">
                         <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Inserir Link</h3>
                         
                         <div class="space-y-4">
                             <div>
                                 <label for="linkText" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                     Texto do Link
                                 </label>
                                 <input type="text" 
                                        id="linkText" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                        placeholder="Digite o texto que será exibido">
                             </div>
                             
                             <div>
                                 <label for="linkUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                     URL do Link
                                 </label>
                                 <input type="url" 
                                        id="linkUrl" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                        placeholder="https://exemplo.com">
                             </div>
                         </div>
                         
                         <div class="flex justify-end space-x-3 mt-6">
                             <button type="button" 
                                     onclick="document.getElementById('linkModal').classList.add('hidden'); document.getElementById('linkText').value = ''; document.getElementById('linkUrl').value = '';" 
                                     class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                 Cancelar
                             </button>
                             <button type="button" 
                                     onclick="insertLinkFromModal()" 
                                     class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                 Inserir Link
                             </button>
                         </div>
                     </div>
                 </div>
                
                <!-- Category -->
                <div>
                    <label for="portfolio_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria *</label>
                    <select name="portfolio_category_id" id="portfolio_category_id" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('portfolio_category_id') border-red-300 @enderror">
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('portfolio_category_id', $work->portfolio_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('portfolio_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Client -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                    <select name="client_id" id="client_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('client_id') border-red-300 @enderror">
                        <option value="">Selecione um cliente (opcional)</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $work->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Authors -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Autores</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3">
                        @foreach($authors as $author)
                            <label class="flex items-center">
                                <input type="checkbox" name="authors[]" value="{{ $author->id }}"
                                       {{ in_array($author->id, old('authors', $work->authors->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $author->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('authors')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Project Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL do Projeto</label>
                        <input type="url" name="project_url" id="project_url" value="{{ old('project_url', $work->project_url ?? '') }}"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('project_url') border-red-300 @enderror">
                        @error('project_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="completion_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Conclusão</label>
                        <input type="date" name="completion_date" id="completion_date" value="{{ old('completion_date', $work->completion_date?->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('completion_date') border-red-300 @enderror">
                        @error('completion_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Technologies -->
                <div>
                    <label for="technologies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tecnologias Utilizadas</label>
                    <input type="text" name="technologies" id="technologies" value="{{ is_array(old('technologies', $work->technologies)) ? implode(', ', old('technologies', $work->technologies)) : old('technologies', $work->technologies) }}"
                           placeholder="Ex: Laravel, Vue.js, Tailwind CSS"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('technologies') border-red-300 @enderror">
                    @error('technologies')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Step 2: Images -->
            <div x-show="currentStep === 2" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Imagens do Trabalho</h2>
                
                <!-- Featured Image Upload -->
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Imagem Destacada (Thumbnail)
                    </label>
                    
                    @if($work->featured_image)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Imagem destacada atual:</p>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $work->featured_image) }}" alt="Imagem destacada atual" class="w-32 h-32 object-cover rounded-lg border-2 border-green-500">
                                <span class="absolute -top-2 -right-2 bg-green-600 text-white text-xs px-2 py-1 rounded">Atual</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Preview Container for New Image -->
                    <div id="featured-image-preview" class="hidden mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview da nova imagem selecionada:</p>
                        <div class="relative inline-block">
                            <img id="featured-image-preview-img" src="" alt="Preview da nova imagem destacada" class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500">
                            <button type="button" onclick="removeFeaturedImagePreview()" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                                ×
                            </button>
                        </div>
                    </div>
                    
                    <div id="featured-image-upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="featured_image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>{{ $work->featured_image ? 'Substituir imagem destacada' : 'Selecionar imagem destacada' }}</span>
                                    <input id="featured_image" name="featured_image" type="file" class="sr-only" accept="image/*" onchange="previewFeaturedImage(this)">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF até 2MB</p>
                        </div>
                    </div>
                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Esta imagem será usada como thumbnail na listagem do portfólio</p>
                </div>
                
                <!-- Current Images -->
                @if($work->images->count() > 0)
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Imagens Atuais</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Arraste as imagens para reordenar, use os botões de seta ou defina a ordem numericamente</p>
                        
                        <div id="existing-images-container" class="space-y-3">
                            @foreach($work->images->sortBy('sort_order') as $image)
                                <div class="existing-image-item bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600" 
                                     data-image-id="{{ $image->id }}" 
                                     data-sort-order="{{ $image->sort_order }}" 
                                     x-data="{ marked: false }">
                                    <!-- Mobile Layout (below 600px) -->
                                    <div class="flex flex-col gap-3 sm:hidden">
                                        <!-- Top Row: Image + Basic Info -->
                                        <div class="flex items-center gap-3">
                                            <!-- Drag Handle -->
                                            <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M7 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                                                </svg>
                                            </div>
                                            
                                            <!-- Image Thumbnail (smaller for mobile) -->
                                            <div class="relative">
                                                <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" 
                                                     class="w-12 h-12 object-cover rounded-lg" 
                                                     :class="marked ? 'opacity-50' : ''">
                                                @if($image->is_cover)
                                                    <span class="absolute -top-1 -right-1 bg-green-600 text-white text-xs px-1 py-0.5 rounded text-[10px]">Capa</span>
                                                @endif
                                            </div>
                                            
                                            <!-- Image Info -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $image->original_name ?? 'Imagem ' . $loop->iteration }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $image->formatted_file_size ?? '' }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Bottom Row: Controls -->
                                        <div class="flex items-center justify-between gap-2">
                                            <!-- Order Controls -->
                                            <div class="flex items-center gap-2">
                                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Ordem:</label>
                                                <input type="number" 
                                                       class="order-input w-12 px-1 py-1 text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded focus:ring-blue-500 focus:border-blue-500" 
                                                       value="{{ $image->sort_order }}" 
                                                       min="1" 
                                                       data-image-id="{{ $image->id }}" 
                                                       @change="updateImageOrder({{ $image->id }}, $event.target.value)">
                                                
                                                <!-- Arrow Buttons -->
                                                <div class="flex gap-1">
                                                    <button type="button" 
                                                            class="move-up-btn p-1 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                                            @click="moveImageUp({{ $image->id }})" 
                                                            title="Mover para cima">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                    </button>
                                                    <button type="button" 
                                                            class="move-down-btn p-1 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                                            @click="moveImageDown({{ $image->id }})" 
                                                            title="Mover para baixo">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex items-center gap-1">
                                                <!-- Featured Button -->
                                                <button type="button" 
                                                        @click="setFeaturedImage({{ $image->id }})" 
                                                        class="p-1.5 transition-colors" 
                                                        :class="featuredImageId == {{ $image->id }} ? 'text-yellow-500 hover:text-yellow-600' : 'text-gray-400 hover:text-yellow-500'" 
                                                        :title="featuredImageId == {{ $image->id }} ? 'Imagem destacada' : 'Marcar como destacada'">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Delete Button -->
                                                <button type="button" 
                                                        @click="marked = !marked; toggleImageForDeletion({{ $image->id }})" 
                                                        class="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400" 
                                                        :class="marked ? 'text-red-600 dark:text-red-400' : ''" 
                                                        title="Marcar para exclusão">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="marked" class="text-xs text-red-600 dark:text-red-400 font-medium">Excluir</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desktop Layout (600px and above) -->
                                    <div class="hidden sm:flex items-center gap-4">
                                        <!-- Drag Handle -->
                                        <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Image Thumbnail -->
                                        <div class="relative">
                                            <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" 
                                                 class="w-20 h-20 object-cover rounded-lg" 
                                                 :class="marked ? 'opacity-50' : ''">
                                            @if($image->is_cover)
                                                <span class="absolute -top-2 -right-2 bg-green-600 text-white text-xs px-2 py-1 rounded">Capa</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Order Controls -->
                                        <div class="flex-1 flex items-center gap-4">
                                            <!-- Order Number Input -->
                                            <div class="flex items-center gap-2">
                                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Ordem:</label>
                                                <input type="number" 
                                                       class="order-input w-16 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded focus:ring-blue-500 focus:border-blue-500" 
                                                       value="{{ $image->sort_order }}" 
                                                       min="1" 
                                                       data-image-id="{{ $image->id }}" 
                                                       @change="updateImageOrder({{ $image->id }}, $event.target.value)">
                                            </div>
                                            
                                            <!-- Arrow Buttons -->
                                            <div class="flex flex-col gap-1">
                                                <button type="button" 
                                                        class="move-up-btn p-1 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                                        @click="moveImageUp({{ $image->id }})" 
                                                        title="Mover para cima">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                </button>
                                                <button type="button" 
                                                        class="move-down-btn p-1 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                                        @click="moveImageDown({{ $image->id }})" 
                                                        title="Mover para baixo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Image Info -->
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $image->original_name ?? 'Imagem ' . $loop->iteration }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $image->formatted_file_size ?? '' }}</p>
                                        </div>
                                        
                                        <!-- Featured Button -->
                                        <div class="flex items-center gap-2">
                                            <button type="button" 
                                                    @click="setFeaturedImage({{ $image->id }})" 
                                                    class="p-2 transition-colors" 
                                                    :class="featuredImageId == {{ $image->id }} ? 'text-yellow-500 hover:text-yellow-600' : 'text-gray-400 hover:text-yellow-500'" 
                                                    :title="featuredImageId == {{ $image->id }} ? 'Imagem destacada' : 'Marcar como destacada'">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Delete Button -->
                                        <div class="flex items-center gap-2">
                                            <button type="button" 
                                                    @click="marked = !marked; toggleImageForDeletion({{ $image->id }})" 
                                                    class="p-2 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400" 
                                                    :class="marked ? 'text-red-600 dark:text-red-400' : ''" 
                                                    title="Marcar para exclusão">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                            <div x-show="marked" class="text-xs text-red-600 dark:text-red-400 font-medium">Excluir</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Hidden inputs for image ordering -->
                        <input type="hidden" name="delete_images" id="delete_images" value="">
                        <input type="hidden" name="image_orders" id="image_orders" value="">
                        <input type="hidden" name="featured_image_id" id="featured_image_id" value="{{ $work->featuredImage ? $work->featuredImage->id : '' }}">
                        
                        <div class="flex flex-wrap gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span>💡 Dicas:</span>
                            <span>• Arraste pela alça para reordenar</span>
                            <span>• Use as setas ↑↓ para mover</span>
                            <span>• Digite a ordem diretamente no campo numérico</span>
                        </div>
                    </div>
                @endif
                
                <!-- Add New Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adicionar Novas Imagens à Galeria</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors"
                         @dragover.prevent @drop.prevent="handleDrop($event)">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="images" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Clique para selecionar</span>
                                    <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="handleFileSelect($event)">
                                </label>
                                <p class="pl-1">ou arraste e solte</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF até 10MB cada</p>
                        </div>
                    </div>
                    @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- New Images Preview -->
                <div x-show="selectedImages.length > 0" class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Novas Imagens</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="(image, index) in selectedImages" :key="index">
                            <div class="relative group">
                                <img :src="image.preview" :alt="image.name" class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button type="button" @click="removeImage(index)" class="text-white hover:text-red-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute top-2 left-2">
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Nova</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: SEO & Publication -->
            <div x-show="currentStep === 3" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">SEO & Publicação</h2>
                
                <!-- Meta Title -->
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Título (SEO)</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $work->meta_title) }}"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('meta_title') border-red-300 @enderror">
                    @error('meta_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Meta Description -->
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Descrição (SEO)</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('meta_description') border-red-300 @enderror">{{ old('meta_description', $work->meta_description) }}</textarea>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status Options -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Opções de Publicação</h3>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="status" value="draft" {{ old('status', $work->status) === 'draft' ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Rascunho - Não visível no site público</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="status" value="published" {{ old('status', $work->status) === 'published' ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Publicado - Visível no site público</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Featured -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $work->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="is_featured" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Trabalho em destaque</label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Trabalhos em destaque aparecem em posição de destaque no site</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <!-- Botão Anterior -->
                    <button type="button" @click="previousStep()" x-show="currentStep > 1"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline ml-2">Anterior</span>
                    </button>
                    
                    <!-- Botões Cancelar e Próximo/Salvar -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('portfolio.works.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors text-center w-full sm:w-auto">
                            Cancelar
                        </a>
                        
                        <button type="button" @click="nextStep()" x-show="currentStep < 3"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors flex items-center justify-center w-full sm:w-auto">
                            <i class="fas fa-arrow-right"></i>
                            <span class="hidden sm:inline ml-2">Próximo</span>
                        </button>
                        
                        <button type="submit" x-show="currentStep === 3"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors w-full sm:w-auto">
                            Salvar Alterações
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function workForm() {
    return {
        currentStep: 1,
        selectedImages: [],
        imagesToDelete: [],
        featuredImageId: {{ $work->featuredImage ? $work->featuredImage->id : 'null' }},
        form: {
            title: '{{ $work->title }}',
            slug: '{{ $work->slug }}'
        },
        
        init() {
            // Inicializar drag-and-drop para imagens existentes
            this.$nextTick(() => {
                this.initializeDragAndDrop();
                this.updateImageOrders();
            });
        },
        
        submitForm(event) {
            console.log('Enviando formulário de edição...');
            console.log('Imagens selecionadas:', this.selectedImages.length);
            
            // Log dos arquivos no input
            const fileInput = document.getElementById('images');
            console.log('Arquivos no input para envio:', fileInput.files.length);
            for (let i = 0; i < fileInput.files.length; i++) {
                console.log(`Arquivo ${i}:`, fileInput.files[i].name, fileInput.files[i].size + ' bytes');
            }
            
            // Processar technologies como array
            const technologiesInput = document.getElementById('technologies');
            const technologiesValue = technologiesInput.value.trim();
            
            if (technologiesValue) {
                // Converter string separada por vírgulas em array
                const technologiesArray = technologiesValue.split(',').map(tech => tech.trim()).filter(tech => tech.length > 0);
                
                // Remover o input original
                technologiesInput.remove();
                
                // Criar inputs hidden para cada tecnologia
                const form = event.target;
                technologiesArray.forEach((tech, index) => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `technologies[${index}]`;
                    hiddenInput.value = tech;
                    form.appendChild(hiddenInput);
                });
            }
            
            return true; // Permitir envio do formulário
        },
        
        setStep(step) {
            this.currentStep = step;
        },
        
        nextStep() {
            if (this.currentStep < 3) {
                this.currentStep++;
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        generateSlug() {
            if (!this.form.slug || this.slugAutoGenerated) {
                this.form.slug = this.form.title
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '') // Remove accents
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-') // Replace multiple hyphens with single
                    .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
                
                document.getElementById('slug').value = this.form.slug;
                this.slugAutoGenerated = true;
            }
        },
        
        handleFileSelect(event) {
            // Método simplificado - deixar o input file gerenciar os arquivos diretamente
            const files = event.target.files;
            console.log('Arquivos selecionados:', files.length);
            
            // Limpar array de imagens selecionadas e recriar com base nos arquivos do input
            this.selectedImages = [];
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedImages.push({
                            file: file,
                            name: file.name,
                            preview: e.target.result,
                            index: index
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        
        handleDrop(event) {
            // Para drag & drop, vamos usar o método tradicional
            const files = event.dataTransfer.files;
            const fileInput = document.getElementById('images');
            
            // Atualizar o input file diretamente
            fileInput.files = files;
            
            // Processar para preview
            this.handleFileSelect({ target: { files: files } });
        },
        
        processFiles(files) {
            // Método removido - funcionalidade movida para handleFileSelect
        },
        
        removeImage(index) {
            // Remover da lista de preview
            this.selectedImages.splice(index, 1);
            
            // Criar novo FileList sem o arquivo removido
            const fileInput = document.getElementById('images');
            const dt = new DataTransfer();
            
            this.selectedImages.forEach(image => {
                dt.items.add(image.file);
            });
            
            fileInput.files = dt.files;
            console.log('Arquivo removido. Arquivos restantes:', fileInput.files.length);
        },
        

        
        toggleImageForDeletion(imageId) {
            const index = this.imagesToDelete.indexOf(imageId);
            if (index > -1) {
                this.imagesToDelete.splice(index, 1);
            } else {
                this.imagesToDelete.push(imageId);
            }
            document.getElementById('delete_images').value = this.imagesToDelete.join(',');
        },
        
        setFeaturedImage(imageId) {
            // Atualizar a variável reativa
            this.featuredImageId = imageId;
            
            // Atualizar o campo hidden com o ID da imagem destacada
            document.getElementById('featured_image_id').value = imageId;
        },
        
        // Métodos de reordenação de imagens existentes
        moveImageUp(imageId) {
            const container = document.getElementById('existing-images-container');
            const imageDiv = document.querySelector(`[data-image-id="${imageId}"]`);
            const previousSibling = imageDiv.previousElementSibling;
            
            if (previousSibling) {
                container.insertBefore(imageDiv, previousSibling);
                this.updateImageOrders();
            }
        },
        
        moveImageDown(imageId) {
            const container = document.getElementById('existing-images-container');
            const imageDiv = document.querySelector(`[data-image-id="${imageId}"]`);
            const nextSibling = imageDiv.nextElementSibling;
            
            if (nextSibling) {
                container.insertBefore(nextSibling, imageDiv);
                this.updateImageOrders();
            }
        },
        
        updateImageOrder(imageId, newOrder) {
            const imageDiv = document.querySelector(`[data-image-id="${imageId}"]`);
            const orderInput = imageDiv.querySelector('.image-order-input');
            
            if (orderInput) {
                orderInput.value = newOrder;
                this.reorderImagesByNumber();
            }
        },
        
        reorderImagesByNumber() {
            const container = document.getElementById('existing-images-container');
            const imageDivs = Array.from(container.children);
            
            // Ordenar por valor do input numérico
            imageDivs.sort((a, b) => {
                const orderA = parseInt(a.querySelector('.image-order-input').value) || 0;
                const orderB = parseInt(b.querySelector('.image-order-input').value) || 0;
                return orderA - orderB;
            });
            
            // Reordenar no DOM
            imageDivs.forEach(div => container.appendChild(div));
            
            this.updateImageOrders();
        },
        
        updateImageOrders() {
            const container = document.getElementById('existing-images-container');
            const imageDivs = Array.from(container.children);
            const orders = {};
            
            imageDivs.forEach((div, index) => {
                const imageId = div.getAttribute('data-image-id');
                const orderInput = div.querySelector('.image-order-input');
                const newOrder = index + 1;
                
                if (orderInput) {
                    orderInput.value = newOrder;
                }
                
                orders[imageId] = newOrder;
            });
            
            // Atualizar campo hidden com as novas ordens
            document.getElementById('image_orders').value = JSON.stringify(orders);
        },
        
        initializeDragAndDrop() {
            const container = document.getElementById('existing-images-container');
            if (!container) return;
            
            // Tornar os itens arrastáveis
            const imageDivs = container.querySelectorAll('[data-image-id]');
            imageDivs.forEach(div => {
                div.draggable = true;
                
                div.addEventListener('dragstart', (e) => {
                    e.dataTransfer.setData('text/plain', div.getAttribute('data-image-id'));
                    div.classList.add('opacity-50');
                });
                
                div.addEventListener('dragend', (e) => {
                    div.classList.remove('opacity-50');
                });
                
                div.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    div.classList.add('border-blue-500', 'border-2');
                });
                
                div.addEventListener('dragleave', (e) => {
                    div.classList.remove('border-blue-500', 'border-2');
                });
                
                div.addEventListener('drop', (e) => {
                    e.preventDefault();
                    div.classList.remove('border-blue-500', 'border-2');
                    
                    const draggedImageId = e.dataTransfer.getData('text/plain');
                    const draggedDiv = document.querySelector(`[data-image-id="${draggedImageId}"]`);
                    const targetDiv = e.currentTarget;
                    
                    if (draggedDiv && targetDiv && draggedDiv !== targetDiv) {
                        const rect = targetDiv.getBoundingClientRect();
                        const midpoint = rect.top + rect.height / 2;
                        
                        if (e.clientY < midpoint) {
                            container.insertBefore(draggedDiv, targetDiv);
                        } else {
                            container.insertBefore(draggedDiv, targetDiv.nextSibling);
                        }
                        
                        this.updateImageOrders();
                    }
                });
            });
        }
    }
}
</script>
@endpush
@endsection

@push('scripts')
<script>
// Editor de Texto Rico - Variáveis globais
let editorHistory = [];
let historyIndex = -1;
let currentEditor = null;

// Salvar estado do editor
function saveState(editorId) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    
    const content = editor.innerHTML;
    
    // Remove estados futuros se estivermos no meio do histórico
    if (historyIndex < editorHistory.length - 1) {
        editorHistory = editorHistory.slice(0, historyIndex + 1);
    }
    
    editorHistory.push(content);
    
    // Limita o histórico a 50 estados
    if (editorHistory.length > 50) {
        editorHistory.shift();
    } else {
        historyIndex++;
    }
}

// Formatação de texto
function formatText(command) {
    saveState('content-editor');
    document.execCommand(command, false, null);
    updateHiddenInput('content-editor', 'content');
}

// Mostrar modal de link
function showLinkModal() {
    document.getElementById('linkModal').classList.remove('hidden');
    document.getElementById('linkText').focus();
}

// Inserir link do modal
function insertLinkFromModal() {
    const linkText = document.getElementById('linkText').value;
    const linkUrl = document.getElementById('linkUrl').value;
    
    if (linkText && linkUrl) {
        saveState('content-editor');
        
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            const link = document.createElement('a');
            link.href = linkUrl;
            link.textContent = linkText;
            link.target = '_blank';
            
            range.deleteContents();
            range.insertNode(link);
            
            // Limpar seleção
            selection.removeAllRanges();
        }
        
        updateHiddenInput('content-editor', 'content');
    }
    
    // Fechar modal
    document.getElementById('linkModal').classList.add('hidden');
    document.getElementById('linkText').value = '';
    document.getElementById('linkUrl').value = '';
}

// Inserir quebra de linha
function insertLineBreak() {
    saveState('content-editor');
    document.execCommand('insertHTML', false, '<br>');
    updateHiddenInput('content-editor', 'content');
}

// Desfazer edição
function undoEdit() {
    if (historyIndex > 0) {
        historyIndex--;
        const editor = document.getElementById('content-editor');
        editor.innerHTML = editorHistory[historyIndex];
        updateHiddenInput('content-editor', 'content');
    }
}

// Atualizar campo hidden
function updateHiddenInput(editorId, hiddenInputId) {
    const editor = document.getElementById(editorId);
    const hiddenInput = document.getElementById(hiddenInputId);
    
    if (editor && hiddenInput) {
        hiddenInput.value = editor.innerHTML;
    }
}

// Inicialização do editor
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('content-editor');
    
    if (editor) {
        // Salvar estado inicial
        saveState('content-editor');
        
        // Atualizar campo hidden quando o conteúdo mudar
        editor.addEventListener('input', function() {
            updateHiddenInput('content-editor', 'content');
        });
        
        // Sincronizar antes do envio do formulário
        const form = editor.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                updateHiddenInput('content-editor', 'content');
            });
        }
    }
    
    // Inicializar drag-and-drop para imagens existentes
    // Aguardar Alpine.js estar pronto
    setTimeout(function() {
        const workFormElement = document.querySelector('[x-data*="workForm"]');
        if (workFormElement && workFormElement._x_dataStack) {
            const workFormData = workFormElement._x_dataStack[0];
            if (workFormData.initializeDragAndDrop) {
                workFormData.initializeDragAndDrop();
            }
            if (workFormData.updateImageOrders) {
                workFormData.updateImageOrders();
            }
        }
    }, 100);
});

// Featured Image Preview Functions
function previewFeaturedImage(input) {
    const file = input.files[0];
    if (file) {
        // Validar tipo de arquivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Por favor, selecione apenas arquivos de imagem (PNG, JPG, GIF).');
            input.value = '';
            return;
        }
        
        // Validar tamanho do arquivo (2MB)
        const maxSize = 2 * 1024 * 1024; // 2MB em bytes
        if (file.size > maxSize) {
            alert('O arquivo deve ter no máximo 2MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('featured-image-preview');
            const previewImg = document.getElementById('featured-image-preview-img');
            const uploadArea = document.getElementById('featured-image-upload-area');
            
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadArea.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeFeaturedImagePreview() {
    const previewContainer = document.getElementById('featured-image-preview');
    const previewImg = document.getElementById('featured-image-preview-img');
    const uploadArea = document.getElementById('featured-image-upload-area');
    const fileInput = document.getElementById('featured_image');
    
    previewImg.src = '';
    previewContainer.classList.add('hidden');
    uploadArea.classList.remove('hidden');
    fileInput.value = '';
}
</script>

<style>
.editor-btn {
    @apply px-2 py-1 text-sm bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors;
}

.editor-btn:hover {
    @apply bg-gray-100 dark:bg-gray-500;
}

#content-editor:empty:before {
    content: attr(placeholder);
    color: #9CA3AF;
    pointer-events: none;
}

#content-editor:focus:before {
    content: '';
}
</style>
@endpush