@extends('layouts.public')

@section('title', 'Portfólio - Danilo Miguel')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <header class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <!-- Logo/Title -->
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-wide">
                        PORTFÓLIO
                    </h1>
                </div>
                
                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition duration-300 text-sm">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}" class="bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800 transition duration-300">
                        Cadastrar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Category Filter -->
    <section class="bg-white py-8 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-4">
                <button class="category-filter active px-4 py-2 text-sm font-medium text-gray-900 bg-gray-100 rounded-full hover:bg-gray-200 transition duration-300" data-category="all">
                    Todos
                </button>
                @foreach($portfolioCategories as $category)
                <button class="category-filter px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition duration-300" data-category="{{ $category->slug }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Portfolio Grid -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="portfolio-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @forelse($portfolioWorks as $work)
                <div class="portfolio-item group cursor-pointer" 
                     data-category="{{ $work->category ? $work->category->slug : 'uncategorized' }}"
                     data-work-id="{{ $work->id }}"
                     data-work-title="{{ $work->title }}"
                     data-work-description="{{ $work->description ?? '' }}"
                     data-work-content="{{ $work->content ?? '' }}"
                     data-work-category="{{ $work->category ? $work->category->name : 'Sem categoria' }}"
                     data-work-client="{{ $work->clientRelation ? $work->clientRelation->name : ($work->client ?? '') }}"
                     data-work-date="{{ $work->created_at->format('d/m/Y') }}"
                     data-work-url="{{ $work->project_url ?? '' }}"
                     data-work-technologies="{{ $work->technologies ? (is_array($work->technologies) ? json_encode($work->technologies) : $work->technologies) : '[]' }}"
                     data-work-images="{{ $work->images->pluck('path')->toJson() }}"
                     data-thumb-image="{{ $work->featured_image ?: ($work->images && $work->images->count() > 0 ? $work->images->first()->path : '') }}"
                     onclick="openPortfolioModal(this)">
                    <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Image -->
                        <div class="aspect-square bg-gray-100">
                            @if($work->featured_image)
                                <img src="{{ asset('storage/' . $work->featured_image) }}" 
                                     alt="{{ $work->title }}" 
                                     class="w-full h-full object-cover transition-all duration-300 group-hover:brightness-110 group-hover:opacity-90">
                            @elseif($work->images && $work->images->count() > 0)
                                <img src="{{ asset('storage/' . $work->images->first()->path) }}" 
                                     alt="{{ $work->title }}" 
                                     class="w-full h-full object-contain transition-all duration-300 group-hover:brightness-110 group-hover:opacity-90">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 transition-all duration-300 group-hover:brightness-110">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Overlay with category and title -->
                        <div class="absolute inset-0 bg-black bg-opacity-70 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end pointer-events-none group-hover:pointer-events-auto">
                            <div class="p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                @if($work->category)
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-white bg-opacity-20 rounded mb-2">
                                    {{ $work->category->name }}
                                </span>
                                @endif
                                <h3 class="text-sm font-medium leading-tight">
                                    {{ $work->title }}
                                </h3>
                                <p class="text-xs text-gray-200 mt-1">Clique para ver detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Placeholder items when no portfolio works exist -->
                @for($i = 1; $i <= 20; $i++)
                <div class="portfolio-item group cursor-pointer" data-category="placeholder">
                    <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="aspect-square bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs text-gray-500">Projeto {{ $i }}</span>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-black bg-opacity-70 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end pointer-events-none group-hover:pointer-events-auto">
                            <div class="p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-white bg-opacity-20 rounded mb-2">
                                    {{ ['Diagramação', 'Ilustração', 'Jogos', 'Tipografia'][array_rand(['Diagramação', 'Ilustração', 'Jogos', 'Tipografia'])] }}
                                </span>
                                <h3 class="text-sm font-medium leading-tight">
                                    Projeto de Exemplo {{ $i }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">QUEM SOU EU?</h2>
            
            <div class="flex flex-col md:flex-row items-center gap-8">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold">DM</span>
                    </div>
                </div>
                
                <!-- Profile Text -->
                <div class="flex-1 text-left">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">DANILO MIGUEL</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Designer Gráfico especializado em diagramação, ilustração e desenvolvimento de jogos educativos. 
                        Com mais de 10 anos de experiência, trabalho criando soluções visuais que comunicam e educam.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Minha paixão é transformar ideias complexas em designs simples e eficazes, 
                        sempre focando na experiência do usuário e na funcionalidade.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-gray-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-8">CONTATO</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Email</h3>
                    <p class="text-gray-300">contato@danilomiguel.com.br</p>
                </div>
                
                <div>
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">WhatsApp</h3>
                    <p class="text-gray-300">(11) 99999-9999</p>
                </div>
                
                <div>
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Portfolio</h3>
                    <p class="text-gray-300">danilomiguel.com.br</p>
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-gray-700">
                <p class="text-gray-400">&copy; {{ date('Y') }} Danilo Miguel. Todos os direitos reservados.</p>
            </div>
        </div>
    </section>
</div>

<!-- Portfolio Modal -->
<div id="portfolioModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4">
    <!-- Close Button (Outside Modal) -->
    <button onclick="closePortfolioModal()" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors z-60">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Modal Header - Título + Descrição Curta -->
        <div class="p-6 border-b border-gray-200 flex-shrink-0">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 text-center mb-3"></h2>
            
            <!-- Descrição Curta -->
            <div id="shortDescriptionSection" class="hidden mb-4">
                <p id="modalShortDescription" class="text-gray-600 text-center leading-relaxed"></p>
            </div>
            
            <div class="text-center">
                <span id="modalCategory" class="inline-block px-3 py-1 text-sm font-medium bg-gray-100 text-gray-700 rounded-full"></span>
            </div>
            
            <!-- Tecnologias como Tags -->
            <div id="technologiesSection" class="hidden mt-4">
                <div class="flex flex-wrap justify-center gap-2" id="technologiesTags">
                    <!-- Tags serão inseridas aqui pelo JavaScript -->
                </div>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Images Container - Vertical Stack -->
            <div id="imagesContainer" class="space-y-0">
                <!-- Images will be inserted here by JavaScript -->
            </div>
            
            <!-- Botão de Download (se tiver URL) -->
            <div id="downloadSection" class="hidden p-6 text-center border-b border-gray-200">
                <a id="downloadButton" href="#" target="_blank" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                </a>
            </div>
            
            <!-- Project Details -->
            <div class="p-6 bg-gray-50">
                <div class="space-y-4">
                    <!-- Client -->
                    <div id="clientSection" class="hidden">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Cliente</h3>
                        <p id="modalClient" class="text-lg text-gray-900"></p>
                    </div>
                    
                    <!-- Date -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Data</h3>
                        <p id="modalDate" class="text-lg text-gray-900"></p>
                    </div>
                    
                    <!-- Conteúdo Completo -->
                    <div id="fullContentSection" class="hidden">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Descrição Completa</h3>
                        <div id="modalFullContent" class="text-gray-700 leading-relaxed prose prose-sm max-w-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Filter Functionality
    const filterButtons = document.querySelectorAll('.category-filter');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active', 'bg-gray-100', 'text-gray-900'));
            filterButtons.forEach(btn => btn.classList.add('text-gray-600'));
            this.classList.add('active', 'bg-gray-100', 'text-gray-900');
            this.classList.remove('text-gray-600');
            
            // Filter portfolio items
            portfolioItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                
                if (category === 'all' || itemCategory === category) {
                    item.style.display = 'block';
                    item.classList.remove('hidden');
                } else {
                    item.style.display = 'none';
                    item.classList.add('hidden');
                }
            });
        });
    });
    
    // Close modal when clicking outside
    document.getElementById('portfolioModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePortfolioModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePortfolioModal();
        }
    });
});

// Portfolio Modal Functions
function openPortfolioModal(element) {
    const modal = document.getElementById('portfolioModal');
    const title = element.getAttribute('data-work-title');
    const description = element.getAttribute('data-work-description');
    const content = element.getAttribute('data-work-content');
    const category = element.getAttribute('data-work-category');
    const client = element.getAttribute('data-work-client');
    const date = element.getAttribute('data-work-date');
    const projectUrl = element.getAttribute('data-work-url');
    const technologies = element.getAttribute('data-work-technologies');
    const images = JSON.parse(element.getAttribute('data-work-images'));
    const thumbImage = element.getAttribute('data-thumb-image');
    
    // Set modal content
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalCategory').textContent = category;
    document.getElementById('modalDate').textContent = date;
    
    // Handle short description section (no topo)
    const shortDescriptionSection = document.getElementById('shortDescriptionSection');
    if (description && description.trim() !== '') {
        document.getElementById('modalShortDescription').textContent = description;
        shortDescriptionSection.classList.remove('hidden');
    } else {
        shortDescriptionSection.classList.add('hidden');
    }
    
    // Handle technologies section
    const technologiesSection = document.getElementById('technologiesSection');
    if (technologies && technologies.trim() !== '') {
        try {
            const techArray = JSON.parse(technologies);
            if (techArray && techArray.length > 0) {
                createTechnologyTags(techArray);
                technologiesSection.classList.remove('hidden');
            } else {
                technologiesSection.classList.add('hidden');
            }
        } catch (e) {
            technologiesSection.classList.add('hidden');
        }
    } else {
        technologiesSection.classList.add('hidden');
    }
    
    // Handle client section
    const clientSection = document.getElementById('clientSection');
    if (client && client.trim() !== '') {
        document.getElementById('modalClient').textContent = client;
        clientSection.classList.remove('hidden');
    } else {
        clientSection.classList.add('hidden');
    }
    
    // Handle project URL section (botão de download)
    const downloadSection = document.getElementById('downloadSection');
    if (projectUrl && projectUrl.trim() !== '') {
        document.getElementById('downloadButton').href = projectUrl;
        downloadSection.classList.remove('hidden');
    } else {
        downloadSection.classList.add('hidden');
    }
    
    // Handle full content section (no final)
    const fullContentSection = document.getElementById('fullContentSection');
    if (content && content.trim() !== '') {
        document.getElementById('modalFullContent').innerHTML = content;
        fullContentSection.classList.remove('hidden');
    } else {
        fullContentSection.classList.add('hidden');
    }
    
    // Prepare images array - REMOVER a primeira imagem (thumb) do modal
    let allImages = images.map(path => '/storage/' + path);
    
    // Se temos uma thumb image, removê-la das imagens do modal
    if (thumbImage && thumbImage.trim() !== '') {
        const thumbPath = '/storage/' + thumbImage;
        allImages = allImages.filter(img => img !== thumbPath);
    } else if (allImages.length > 0) {
        // Se não tem thumb específica, remove a primeira imagem
        allImages = allImages.slice(1);
    }
    
    // Create vertical image stack (sem a thumb)
    createVerticalImageStack(allImages);
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function createVerticalImageStack(images) {
    const container = document.getElementById('imagesContainer');
    container.innerHTML = ''; // Clear existing content
    
    images.forEach((imagePath, index) => {
        const imageDiv = document.createElement('div');
        imageDiv.className = 'w-full';
        
        const img = document.createElement('img');
        img.src = imagePath;
        img.alt = `Imagem ${index + 1}`;
        img.className = 'w-full h-auto object-cover block';
        img.style.display = 'block'; // Ensure no gaps
        
        imageDiv.appendChild(img);
        container.appendChild(imageDiv);
    });
}

function createTechnologyTags(technologies) {
    const container = document.getElementById('technologiesTags');
    container.innerHTML = ''; // Clear existing content
    
    // Cores para as tags
    const colors = [
        'bg-blue-100 text-blue-800',
        'bg-green-100 text-green-800',
        'bg-purple-100 text-purple-800',
        'bg-yellow-100 text-yellow-800',
        'bg-pink-100 text-pink-800',
        'bg-indigo-100 text-indigo-800',
        'bg-red-100 text-red-800',
        'bg-gray-100 text-gray-800'
    ];
    
    technologies.forEach((tech, index) => {
        const tag = document.createElement('span');
        const colorClass = colors[index % colors.length];
        tag.className = `inline-block px-3 py-1 text-xs font-medium rounded-full ${colorClass}`;
        tag.textContent = tech;
        container.appendChild(tag);
    });
}

function closePortfolioModal() {
    const modal = document.getElementById('portfolioModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}


</script>
@endsection