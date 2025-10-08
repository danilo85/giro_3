<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light only">
    <title>Culinária - Receitas Deliciosas</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/receitas.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/receitas.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/receitas.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Pacifico&family=Alex+Brush&family=Sacramento&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Prevent forced dark mode by browsers */
        :root {
            color-scheme: light only;
        }

        html {
            color-scheme: light only;
        }

        body {
            color-scheme: light only;
        }

        /* Additional protection against browser dark mode */
        * {
            color-scheme: light only !important;
        }

        input, textarea, select, button {
            color-scheme: light only !important;
        }

        :root {
            --primary-dark: #2b363f;
            --primary-orange: #ff8c00;
            --primary-yellow: #ffd700;
            --primary-blue: #4a90e2;
            --primary-red: #e74c3c;
            --primary-white: #fff;
            --light-gray: #f9fafb;
            --border-gray: #e5e7eb;
            --text-gray: #6b7280;
            --warm-orange: #ff7f00;
            --soft-blue: #5ba3f5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            line-height: 1.6;
            color: var(--primary-dark);
            background: #f9fafb;
        }
        
        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            color: var(--primary-dark);
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            position: relative;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Logo Image */
        .logo-image {
            width: 120px;
            height: auto;
            margin: 0 auto 1.5rem;
            display: block;
        }
        
        .header-content h1 {
            font-family: 'Sacramento', cursive;
            font-size: 4rem;
            font-weight: 400;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--primary-orange);
        }
        
        .categories-subtitle {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin: 2rem 0 1rem;
        }
        
        .header-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: var(--text-gray);
            font-weight: 500;
        }
        
        /* Filter Container with Search */
        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }
        
        .search-form {
            display: flex;
            align-items: center;
        }
        
        .search-input-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-toggle {
            background: none;
            border: none;
            color: var(--text-gray);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }
        
        .search-toggle:hover {
            color: var(--primary-orange);
        }
        
        .search-input {
            width: 0;
            opacity: 0;
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-gray);
            border-radius: 8px;
            font-size: 0.9rem;
            background: white;
            margin-left: 0.5rem;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .search-input.expanded {
            width: 200px;
            opacity: 1;
        }
        
        /* Floating Search */
        .floating-search {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 8px 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .floating-search:hover {
            background: rgba(255, 255, 255, 0.98);
            /* box-shadow: 0 4px 20px rgba(0,0,0,0.15); */
        }
        
        .floating-search-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .floating-search-toggle {
            background: none;
            border: none;
            color: var(--primary-orange);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }
        
        .floating-search-toggle:hover {
            background: rgba(255, 140, 0, 0.1);
            transform: scale(1.1);
        }
        
        .floating-search-input {
            width: 0;
            opacity: 0;
            padding: 8px 12px;
            border: none;
            border-radius: 20px;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.8);
            outline: none;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .floating-search-input.expanded {
            width: 200px;
            opacity: 1;
            border: 1px solid rgba(255, 140, 0, 0.3);
        }
        
        .floating-search-input:focus {
            background: white;
            border-color: var(--primary-orange);
        }
        
        /* Category Filter - Simple Style like Portfolio */
        .category-filter-section {
            max-width: 1200px;
            margin: 0 auto 3rem;
            padding: 0 2rem;
        }
        
        .portfolio-filter {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
            margin-top: 0.3rem;
        }
        
        .category-filter {
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s ease;
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            font-family: 'Figtree', sans-serif;
        }
        
        .category-filter:hover,
        .category-filter.active {
            color: var(--primary-orange);
        }
        
        .category-filter::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-orange);
            transition: width 0.3s ease;
        }
        
        .category-filter:hover::after,
        .category-filter.active::after {
            width: 100%;
        }
        
        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .recipe-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            border: none;
            cursor: pointer;
            text-align: center;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .recipe-image-container {
            position: relative;
            width: 100%;
            aspect-ratio: 16/10;
            min-height: 180px;
            overflow: hidden;
        }
        
        .recipe-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: var(--light-gray);
            display: block;
        }
        
        .recipe-content {
            padding: 1.5rem;
        }
        
        .recipe-title {
            font-family: 'Sacramento', cursive;
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 0.5rem;
            color: var(--primary-dark);
            line-height: 1.3;
        }
        
        .recipe-description {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .recipe-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.875rem;
            color: var(--text-gray);
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .recipe-category {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--warm-orange) 100%);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .recipe-time {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }
        
        /* Laravel Pagination Styles */
        .pagination {
            display: flex !important;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
            background: transparent !important;
            border: none !important;
        }
        
        .page-item {
            margin: 0 !important;
        }
        
        .page-link {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 44px !important;
            height: 44px !important;
            padding: 0.5rem 0.75rem !important;
            color: var(--text-dark) !important;
            background-color: var(--primary-white) !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            transition: all 0.3s ease !important;
        }
        
        .page-link:hover {
            color: var(--primary-white) !important;
            background-color: var(--primary-orange) !important;
            border-color: var(--primary-orange) !important;
            transform: translateY(-1px) !important;
        }
        
        .page-item.active .page-link {
            color: var(--primary-white) !important;
            background-color: var(--primary-blue) !important;
            border-color: var(--primary-blue) !important;
        }
        
        .page-item.disabled .page-link {
            color: #9ca3af !important;
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
        }
        
        .page-item.disabled .page-link:hover {
            color: #9ca3af !important;
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            transform: none !important;
        }
        
        /* Pagination arrows */
        .page-link[rel="prev"],
        .page-link[rel="next"] {
            font-weight: 600;
            padding: 0.5rem 1rem;
        }
        
        .page-link[rel="prev"]:before {
            content: "‹";
            margin-right: 0.25rem;
        }
        
        .page-link[rel="next"]:after {
            content: "›";
            margin-left: 0.25rem;
        }
        
        /* Pagination info text */
        .pagination-info {
            text-align: center;
            margin-bottom: 1rem;
            color: var(--text-gray);
            font-size: 0.875rem;
        }
        
        /* No recipes message */
        .no-recipes {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-gray);
        }
        
        .no-recipes i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }
        
        /* Footer */
        .footer-section {
            background: var(--primary-dark);
            color: var(--primary-white);
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 3rem;
            margin-bottom: 2rem;
            align-items: center;
        }
        
        .footer-logo {
            text-align: center;
        }
        
        .footer-logo img {
            height: 100px;
            width: auto;
            filter: brightness(0) invert(1);
        }
        
        .footer-text {
            text-align: left;
        }
        
        .footer-text h3 {
            color: var(--primary-orange);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .footer-text p {
            color: #d1d5db;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
            font-size: 1.2rem;
            color: white;
        }
        
        .footer-copyright {
            border-top: 1px solid #4b5563;
            padding-top: 1.5rem;
            text-align: center;
        }
        
        .footer-copyright p {
            color: #9ca3af;
            font-size: 0.875rem;
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content h1 {
                font-size: 3rem;
            }
            
            .categories-subtitle {
                font-size: 2rem;
            }
            
            .header-content p {
                font-size: 1.1rem;
            }
            
            .logo-image {
                width: 80px;
            }
            
            .search-input.expanded {
                width: 250px;
            }
            
            .floating-search {
                top: 15px;
                right: 15px;
                padding: 6px 12px;
            }
            
            .floating-search-input.expanded {
                width: 160px;
            }
            
            .portfolio-filter {
                flex-wrap: wrap;
                gap: 1rem;
                justify-content: center;
            }
            
            .category-filter {
                font-size: 0.9rem;
                padding: 0.4rem 0;
            }
            
            .recipes-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }
            
            .recipe-image-container {
                aspect-ratio: 4/3;
                min-height: 200px;
            }
            
            .recipe-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .recipe-title {
                font-size: 1.3rem;
            }
            
            .main-content {
                padding: 0 1rem;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }
            
            /* Pagination responsive */
            .pagination {
                gap: 0.25rem;
                flex-wrap: wrap;
            }
            
            .page-link {
                min-width: 40px;
                height: 40px;
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }
            
            .page-link[rel="prev"],
            .page-link[rel="next"] {
                padding: 0.4rem 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .header-content h1 {
                font-size: 2.5rem;
            }
            
            .categories-subtitle {
                font-size: 1.8rem;
            }
            
            .logo-image {
                width: 60px;
            }
            
            .search-input.expanded {
                width: 150px;
            }
            
            .floating-search {
                top: 10px;
                right: 10px;
                left: 10px;
                right: auto;
                margin: 0 auto;
                max-width: 280px;
                padding: 6px 10px;
            }
            
            .floating-search-input.expanded {
                width: 140px;
            }
            
            .header-section {
                padding: 2rem 1rem 1.5rem;
            }
            
            .filter-container {
                flex-direction: column;
                gap: 1rem;
                align-items: center;
            }
            
            .portfolio-filter {
                gap: 0.8rem;
                padding: 0 0.5rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .category-filter {
                font-size: 0.8rem;
                padding: 0.3rem 0;
                min-width: auto;
                white-space: nowrap;
            }
            
            .recipes-grid {
                grid-template-columns: 1fr;
            }
            
            .recipe-image-container {
                aspect-ratio: 4/3;
                min-height: 200px;
            }
            
            .recipe-title {
                font-size: 1.2rem;
            }
            
            .category-filter-section {
                padding: 0 1rem;
            }
            
            /* Pagination mobile */
            .pagination {
                gap: 0.2rem;
                justify-content: center;
            }
            
            .page-link {
                min-width: 36px;
                height: 36px;
                padding: 0.3rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .page-link[rel="prev"],
            .page-link[rel="next"] {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }
            
            .pagination-wrapper {
                margin-top: 2rem;
                padding: 0 1rem;
            }
        }
        
        /* Extra small screens */
        @media (max-width: 320px) {
            .recipe-image-container {
                aspect-ratio: 1/1;
                min-height: 250px;
            }
            
            .portfolio-filter {
                gap: 0.5rem;
                padding: 0 0.25rem;
            }
            
            .category-filter {
                font-size: 0.75rem;
                padding: 0.25rem 0;
            }
            
            .category-filter-section {
                padding: 0 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header-section">
        <div class="header-content">
            <img src="{{ asset('storage/logo_receitas_vert.svg') }}" alt="Logo Receitas" class="logo-image">
            <h1>Receitas</h1>

        </div>
    </header>

    <!-- Floating Search -->
    <div class="floating-search">
        <form method="GET" action="{{ route('culinaria.index') }}" id="floatingSearchForm" class="floating-search-form">
            <button type="button" class="floating-search-toggle" id="floatingSearchToggle">
                <i class="fas fa-search"></i>
            </button>
            <input 
                type="text" 
                name="search" 
                placeholder="Buscar receitas..." 
                class="floating-search-input"
                id="floatingSearchInput"
                value="{{ request('search') }}"
            >
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
        </form>
    </div>

    <!-- Category Filter Section -->
    @if($categories->where('recipes_count', '>', 0)->count() > 0)
    <section class="category-filter-section">
        <div class="portfolio-filter">
            <button class="category-filter {{ !request('category') ? 'active' : '' }}" data-category="all">TODAS</button>
            @foreach($categories->where('recipes_count', '>', 0) as $category)
                <button class="category-filter {{ request('category') == $category->id ? 'active' : '' }}" 
                        data-category="{{ $category->id }}">{{ mb_strtoupper($category->name, 'UTF-8') }}</button>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        @if($recipes->count() > 0)
            <div class="recipes-grid">
                @foreach($recipes as $recipe)
                    <div class="recipe-card" data-category="{{ $recipe->category ? $recipe->category->id : '' }}" onclick="window.location.href='{{ route('culinaria.show', $recipe) }}'">
                        <div class="recipe-image-container">
                            @if($recipe->image_path)
                                <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->name }}" class="recipe-image">
                            @else
                                <div class="recipe-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-orange) 0%, var(--warm-orange) 100%);">
                                    <i class="fas fa-utensils" style="font-size: 2rem; color: white; opacity: 0.8;"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="recipe-content">
                            <h3 class="recipe-title">{{ $recipe->name }}</h3>
                            <p class="recipe-description">{{ $recipe->description }}</p>
                            
                            <div class="recipe-meta">
                                <span class="recipe-category">{{ $recipe->category ? $recipe->category->name : 'Sem categoria' }}</span>
                                <span class="recipe-time">
                                    <i class="fas fa-clock"></i>
                                    {{ $recipe->preparation_time }} min
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $recipes->links('pagination.custom') }}
        </div>
        @else
            <div class="no-recipes">
                <i class="fas fa-search"></i>
                <h3>Nenhuma receita encontrada</h3>
                <p>Tente ajustar a busca ou explore outras receitas.</p>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="{{ asset('storage/logo_receitas_vert.svg') }}" alt="Receitas Deliciosas">
                </div>
                
                <div class="footer-text">
                    <h3>Cozinhe com Paixão</h3>
                    <p>
                        Cada receita é uma jornada de sabores, uma história contada através dos ingredientes. 
                        Aqui você encontra inspiração para criar momentos especiais na sua cozinha.
                    </p>
                    
                    <!-- Social Media Icons -->
                    @php
                        $user = App\Models\User::find(1);
                    @endphp
                    @if($user && ($user->facebook_url || $user->instagram_url || $user->twitter_url || $user->linkedin_url || $user->youtube_url || $user->tiktok_url || $user->whatsapp_url || $user->website_url))
                    <div class="social-icons">
                        @if($user->facebook_url)
                        <a href="{{ $user->facebook_url }}" target="_blank" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        @if($user->instagram_url)
                        <a href="{{ $user->instagram_url }}" target="_blank" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if($user->twitter_url)
                        <a href="{{ $user->twitter_url }}" target="_blank" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                        @if($user->linkedin_url)
                        <a href="{{ $user->linkedin_url }}" target="_blank" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        @endif
                        @if($user->youtube_url)
                        <a href="{{ $user->youtube_url }}" target="_blank" class="social-link" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        @endif
                        @if($user->tiktok_url)
                        <a href="{{ $user->tiktok_url }}" target="_blank" class="social-link" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        @endif
                        @if($user->whatsapp_url)
                        <a href="{{ $user->whatsapp_url }}" target="_blank" class="social-link" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        @endif
                        @if($user->website_url)
                        <a href="{{ $user->website_url }}" target="_blank" class="social-link" title="Website">
                            <i class="fab fa-behance"></i>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="footer-copyright">
                <p>© {{ date('Y') }} Danilo Miguel. Todos os direitos reservados. Feito com ❤️ para os amantes da culinária.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category Filter Functionality
            const categoryFilters = document.querySelectorAll('.category-filter');
            const recipeCards = document.querySelectorAll('.recipe-card');
            
            categoryFilters.forEach(filter => {
                filter.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    
                    // Remove active class from all filters
                    categoryFilters.forEach(f => f.classList.remove('active'));
                    
                    // Add active class to clicked filter
                    this.classList.add('active');
                    
                    // Filter recipes
                    recipeCards.forEach(card => {
                        const cardCategory = card.getAttribute('data-category');
                        
                        if (category === 'all' || cardCategory === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Floating Search Functionality
            const floatingSearchToggle = document.getElementById('floatingSearchToggle');
            const floatingSearchInput = document.getElementById('floatingSearchInput');
            const floatingSearchForm = document.getElementById('floatingSearchForm');
            let isFloatingExpanded = false;

            // Expandir se já houver uma busca ativa
            if (floatingSearchInput.value.trim() !== '') {
                floatingSearchInput.classList.add('expanded');
                isFloatingExpanded = true;
            }

            floatingSearchToggle.addEventListener('click', function() {
                if (!isFloatingExpanded) {
                    floatingSearchInput.classList.add('expanded');
                    isFloatingExpanded = true;
                    setTimeout(() => {
                        floatingSearchInput.focus();
                    }, 300);
                } else {
                    if (floatingSearchInput.value.trim() === '') {
                        floatingSearchInput.classList.remove('expanded');
                        isFloatingExpanded = false;
                    } else {
                        floatingSearchForm.submit();
                    }
                }
            });

            floatingSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    floatingSearchForm.submit();
                }
            });

            floatingSearchInput.addEventListener('blur', function() {
                if (floatingSearchInput.value.trim() === '') {
                    setTimeout(() => {
                        floatingSearchInput.classList.remove('expanded');
                        isFloatingExpanded = false;
                    }, 200);
                }
            });
        });
    </script>
</body>
</html>