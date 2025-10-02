@extends('layouts.public')

@section('title', 'Portfólio - Danilo Miguel')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <header class="bg-white border-b border-gray-100 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo/Title -->
                <div class="flex items-center">
                    <img src="{{ asset('storage/logo_site.svg') }}" alt="Logo do Site" class="h-12 w-auto">
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="nav-link text-gray-700 hover:text-blue-600 transition duration-300 font-medium">Home</a>
                    <a href="#about" class="nav-link text-gray-700 hover:text-blue-600 transition duration-300 font-medium">Sobre</a>
                    <a href="#portfolio" class="nav-link text-gray-700 hover:text-blue-600 transition duration-300 font-medium">Portfólio</a>
                    <a href="#contact" class="nav-link text-gray-700 hover:text-blue-600 transition duration-300 font-medium">Contato</a>
                </nav>
                
                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition duration-300 text-sm flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Entrar
                    </a>
                    @if(App\Models\Setting::isPublicRegistrationEnabled())
                    <a href="{{ route('register') }}" class="bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800 transition duration-300 flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Cadastrar
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content with padding for fixed header -->
    <div class="pt-20">

    <!-- Home Section -->
    <section id="home" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                Bem-vindo ao nosso
                <span class="text-blue-600">Portfólio</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Descubra nossos projetos criativos e soluções inovadoras que transformam ideias em realidade.
            </p>
            <a href="#portfolio" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                Ver Portfólio
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Sobre Nós</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Somos uma equipe apaixonada por criar experiências digitais excepcionais e soluções criativas que fazem a diferença.
                </p>
            </div>
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Nossa Missão</h3>
                    <p class="text-gray-600 mb-6">
                        Transformar ideias em realidade através de design inovador e tecnologia de ponta, 
                        criando soluções que não apenas atendem às necessidades dos nossos clientes, 
                        mas superam suas expectativas.
                    </p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Nossa Visão</h3>
                    <p class="text-gray-600">
                        Ser reconhecidos como líderes em inovação digital, estabelecendo novos padrões 
                        de excelência em cada projeto que desenvolvemos.
                    </p>
                </div>
                <div class="bg-gray-100 rounded-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Nossos Valores</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold mr-3">•</span>
                            <span class="text-gray-600">Excelência em cada detalhe</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold mr-3">•</span>
                            <span class="text-gray-600">Inovação constante</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold mr-3">•</span>
                            <span class="text-gray-600">Transparência e confiança</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold mr-3">•</span>
                            <span class="text-gray-600">Foco no cliente</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    @php
        $user = App\Models\User::first(); // Get the first user for social media display
    @endphp
    @if($user && ($user->facebook_url || $user->instagram_url || $user->twitter_url || $user->linkedin_url || $user->youtube_url || $user->tiktok_url || $user->whatsapp_url || $user->website_url))
    <section class="bg-gray-50 py-4 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center space-x-6">
                <span class="text-sm text-gray-600 font-medium">Siga-me:</span>
                <div class="flex items-center space-x-4">
                    @if($user->facebook_url)
                    <a href="{{ $user->facebook_url }}" target="_blank" class="text-gray-600 hover:text-blue-600 transition duration-300">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    @endif
                    @if($user->instagram_url)
                    <a href="{{ $user->instagram_url }}" target="_blank" class="text-gray-600 hover:text-pink-600 transition duration-300">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    @endif
                    @if($user->twitter_url)
                    <a href="{{ $user->twitter_url }}" target="_blank" class="text-gray-600 hover:text-blue-400 transition duration-300">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    @endif
                    @if($user->linkedin_url)
                    <a href="{{ $user->linkedin_url }}" target="_blank" class="text-gray-600 hover:text-blue-700 transition duration-300">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                    @endif
                    @if($user->youtube_url)
                    <a href="{{ $user->youtube_url }}" target="_blank" class="text-gray-600 hover:text-red-600 transition duration-300">
                        <i class="fab fa-youtube text-lg"></i>
                    </a>
                    @endif
                    @if($user->tiktok_url)
                    <a href="{{ $user->tiktok_url }}" target="_blank" class="text-gray-600 hover:text-black transition duration-300">
                        <i class="fab fa-tiktok text-lg"></i>
                    </a>
                    @endif
                    @if($user->whatsapp_url)
                    <a href="{{ $user->whatsapp_url }}" target="_blank" class="text-gray-600 hover:text-green-600 transition duration-300">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>
                    @endif
                    @if($user->website_url)
                    <a href="{{ $user->website_url }}" target="_blank" class="text-gray-600 hover:text-blue-600 transition duration-300">
                        <i class="fab fa-behance text-lg"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

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

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Nosso Portfólio</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Explore nossa coleção de projetos criativos e soluções inovadoras.
                </p>
            </div>
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
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Entre em Contato</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Vamos conversar sobre seu próximo projeto e como podemos ajudar.
                </p>
            </div>
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
        </div>
    </section>

    </div> <!-- End of main content div -->

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
            
            <!-- Conteúdo Completo - Movido para depois das imagens -->
            <div id="fullContentSection" class="hidden p-6 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Descrição Completa</h3>
                <div id="modalFullContent" class="text-gray-700 leading-relaxed prose prose-sm max-w-none"></div>
            </div>
            
            <!-- Botão de Download Centralizado (se tiver URL) -->
            <div id="downloadSection" class="hidden p-6 text-center border-b border-gray-200">
                <a id="downloadButton" href="#" target="_blank" class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Acessar Projeto
                </a>
            </div>
            
            <!-- Project Details - Removida seção de cliente -->
            <div class="p-6 bg-gray-50">
                <div class="space-y-4">
                    <!-- Date -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Data</h3>
                        <p id="modalDate" class="text-lg text-gray-900"></p>
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
    
    // Client section removed - no longer needed
    
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

// Smooth scrolling for navigation links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerHeight = document.querySelector('header').offsetHeight;
                const targetPosition = target.offsetTop - headerHeight;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Active navigation links based on scroll position
    function updateActiveNavLink() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        const headerHeight = document.querySelector('header').offsetHeight;
        
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - headerHeight - 100;
            const sectionHeight = section.offsetHeight;
            
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('text-blue-600', 'font-bold');
            link.classList.add('text-gray-700');
            
            if (link.getAttribute('href') === '#' + current) {
                link.classList.remove('text-gray-700');
                link.classList.add('text-blue-600', 'font-bold');
            }
        });
    }
    
    // Update active link on scroll
    window.addEventListener('scroll', updateActiveNavLink);
    
    // Update active link on page load
    updateActiveNavLink();
});

</script>
@endsection