<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light only">
    <title>{{ $recipe->name }} - Culinária</title>
    
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
            overflow-x: hidden;
            background: var(--light-gray);
        }
        
        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            color: var(--primary-dark);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Logo Image */
        .logo-image {
            width: 100px;
            height: auto;
            margin: 0 auto 1rem;
            display: block;
        }
        

        
        /* Main Content */
        .main-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 2rem 4rem;
        }
        
        .recipe-header {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 2rem;
        }
        
        .recipe-image-container {
            position: relative;
            height: 400px;
            overflow: hidden;
        }
        
        .recipe-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .recipe-image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .recipe-info {
            padding: 2rem;
        }
        
        .recipe-title {
            font-family: 'Sacramento', cursive;
            font-size: 3.5rem;
            font-weight: 400; /* Sacramento normal weight */
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--primary-orange);
        }
        
        .recipe-description {
            font-size: 1.1rem;
            color: var(--text-gray);
            margin-bottom: 1.5rem;
            line-height: 1.7;
            font-weight: 500;
        }
        
        .recipe-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 6px;
        }
        
        .meta-icon {
            color: var(--primary-orange);
            font-size: 1.1rem;
        }
        
        .meta-label {
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .meta-value {
            color: #6b7280;
        }
        
        .recipe-category-badge {
            display: inline-block;
            background: var(--primary-orange);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        /* Recipe Content */
        .recipe-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .ingredients-section,
        .instructions-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .section-title {
            font-family: 'Sacramento', cursive;
            font-size: 2rem;
            font-weight: 400; /* Sacramento normal weight */
            margin-bottom: 1.5rem;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-icon {
            color: var(--primary-orange);
        }
        
        .ingredients-list {
            list-style: none;
        }
        
        .ingredient-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
            gap: 0.75rem;
        }
        
        .ingredient-item:last-child {
            border-bottom: none;
        }
        
        .ingredient-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid var(--primary-orange);
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        
        .ingredient-checkbox:hover {
            background-color: rgba(255, 140, 0, 0.1);
        }
        
        .ingredient-checkbox.checked {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }
        
        .ingredient-checkbox.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .ingredient-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex: 1;
            transition: all 0.3s ease;
        }
        
        .ingredient-content.checked {
            text-decoration: line-through;
            opacity: 0.6;
        }
        
        .ingredient-name {
            font-weight: 500;
            color: var(--primary-dark);
        }
        
        .ingredient-quantity {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .instructions-content {
            white-space: pre-line;
            line-height: 1.8;
            color: var(--primary-dark);
        }
        
        /* Footer Info */
        .footer-info-section {
            background: var(--primary-dark);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-top: 2rem;
            text-align: center;
            color: var(--primary-white);
        }
        
        .footer-info-logo {
            margin-bottom: 1.5rem;
        }
        
        .footer-info-logo img {
            height: 60px;
            width: auto;
            filter: brightness(0) invert(1);
        }
        
        .footer-info-title {
            font-family: 'Sacramento', cursive;
            font-size: 2rem;
            font-weight: 400;
            color: var(--primary-orange);
            margin-bottom: 1rem;
        }
        
        .footer-info-text {
            color: #d1d5db;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .footer-social-icons {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .footer-social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
            font-size: 1rem;
            color: white;
            transition: all 0.2s ease;
        }
        
        .footer-social-link:hover {
            background: var(--primary-orange);
            transform: translateY(-2px);
        }
        
        .footer-copyright {
            border-top: 1px solid #4b5563;
            padding-top: 1rem;
            margin-top: 1rem;
            color: #9ca3af;
            font-size: 0.8rem;
        }
        
        /* Clear Button */
        .ingredients-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .clear-button {
            background: white;
            border: 1px solid var(--border-gray);
            color: var(--text-gray);
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .clear-button:hover {
            background-color: var(--light-gray);
            border-color: var(--primary-orange);
            color: var(--primary-orange);
        }
        
        .clear-button:active {
            transform: scale(0.98);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-section {
                padding: 1.5rem 1rem 1rem;
            }
            
            .logo-image {
                width: 80px;
            }
            
            .main-content {
                padding: 1.5rem 1rem 2rem;
            }
            
            .recipe-content {
                grid-template-columns: 1fr;
            }
            
            .recipe-meta-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .recipe-meta-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-info-logo img {
                height: 50px;
            }
            
            .footer-info-title {
                font-size: 1.8rem;
            }
            
            .footer-info-text {
                font-size: 0.9rem;
            }
            
            .footer-social-icons {
                gap: 0.5rem;
            }
            
            .footer-social-link {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header-section">
        <div class="header-content">
            <img src="{{ asset('storage/logo_receitas_vert.svg') }}" alt="Logo Receitas" class="logo-image">
            <h1 style="font-family: 'Sacramento', cursive; font-size: 3rem; font-weight: 400; color: var(--primary-orange); margin-bottom: 0.5rem;">{{ $recipe->name }}</h1>
            <p style="color: var(--text-gray); font-size: 1rem; margin-bottom: 1rem;">
                <a href="{{ route('culinaria.index') }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 500;">
                    <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i>Voltar às receitas
                </a>
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Recipe Header -->
        <div class="recipe-header">
            <div class="recipe-image-container">
                @if($recipe->image_path)
                    <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->name }}" class="recipe-image">
                @else
                    <div class="recipe-image-placeholder">
                        <i class="fas fa-utensils" style="font-size: 4rem; color: #d1d5db;"></i>
                    </div>
                @endif
            </div>
            
            <div class="recipe-info">
                <p class="recipe-description">{{ $recipe->description }}</p>
                
                <div class="recipe-meta-grid">
                    <div class="meta-item">
                        <i class="fas fa-clock meta-icon"></i>
                        <div>
                            <div class="meta-label">Tempo</div>
                            <div class="meta-value">{{ $recipe->preparation_time }} min</div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-users meta-icon"></i>
                        <div>
                            <div class="meta-label">Porções</div>
                            <div class="meta-value">{{ $recipe->servings }} {{ $recipe->servings == 1 ? 'pessoa' : 'pessoas' }}</div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-tag meta-icon"></i>
                        <div>
                            <div class="meta-label">Categoria</div>
                            <div class="meta-value">{{ $recipe->category ? $recipe->category->name : 'Sem categoria' }}</div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-user-chef meta-icon"></i>
                        <div>
                            <div class="meta-label">Chef</div>
                            <div class="meta-value">{{ $recipe->user->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipe Content -->
        <div class="recipe-content">
            <!-- Ingredients -->
            <div class="ingredients-section">
                <div class="ingredients-header">
                    <h2 class="section-title" style="margin-bottom: 0;">
                        <i class="fas fa-list-ul section-icon"></i>
                        Ingredientes
                    </h2>
                    <button class="clear-button" onclick="clearAllIngredients()">
                        <i class="fas fa-eraser"></i>
                        Limpar
                    </button>
                </div>
                
                <ul class="ingredients-list">
                    @foreach($recipe->recipeIngredients as $index => $recipeIngredient)
                        <li class="ingredient-item">
                            <div class="ingredient-checkbox" onclick="toggleIngredient({{ $index }})"></div>
                            <div class="ingredient-content" id="ingredient-{{ $index }}">
                                <span class="ingredient-name">{{ $recipeIngredient->ingredient->name }}</span>
                                <span class="ingredient-quantity">
                                    {{ formatQuantityWithUnit($recipeIngredient->quantity, $recipeIngredient->unit) }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Instructions -->
            <div class="instructions-section">
                <h2 class="section-title">
                    <i class="fas fa-clipboard-list section-icon"></i>
                    Modo de Preparo
                </h2>
                
                <div class="instructions-content">{{ $recipe->preparation_method }}</div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="footer-info-section">
            <div class="footer-info-logo">
                <img src="{{ asset('storage/logo_receitas_vert.svg') }}" alt="Receitas Deliciosas">
            </div>
            
            <h3 class="footer-info-title">Cozinhe com Paixão</h3>
            
            <p class="footer-info-text">
                Cada receita é uma jornada de sabores, uma história contada através dos ingredientes. 
                Aqui você encontra inspiração para criar momentos especiais na sua cozinha.
            </p>
            
            <!-- Social Media Icons -->
            @php
                $user = App\Models\User::find(1);
            @endphp
            @if($user && ($user->facebook_url || $user->instagram_url || $user->twitter_url || $user->linkedin_url || $user->youtube_url || $user->tiktok_url || $user->whatsapp_url || $user->website_url))
            <div class="footer-social-icons">
                @if($user->facebook_url)
                <a href="{{ $user->facebook_url }}" target="_blank" class="footer-social-link" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                @endif
                @if($user->instagram_url)
                <a href="{{ $user->instagram_url }}" target="_blank" class="footer-social-link" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                @endif
                @if($user->twitter_url)
                <a href="{{ $user->twitter_url }}" target="_blank" class="footer-social-link" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                @endif
                @if($user->linkedin_url)
                <a href="{{ $user->linkedin_url }}" target="_blank" class="footer-social-link" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                @endif
                @if($user->youtube_url)
                <a href="{{ $user->youtube_url }}" target="_blank" class="footer-social-link" title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                @endif
                @if($user->tiktok_url)
                <a href="{{ $user->tiktok_url }}" target="_blank" class="footer-social-link" title="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
                @endif
                @if($user->whatsapp_url)
                <a href="{{ $user->whatsapp_url }}" target="_blank" class="footer-social-link" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                @endif
                @if($user->website_url)
                <a href="{{ $user->website_url }}" target="_blank" class="footer-social-link" title="Website">
                    <i class="fab fa-behance"></i>
                </a>
                @endif
            </div>
            @endif
            
            <!-- Copyright -->
            <div class="footer-copyright">
                <p>© {{ date('Y') }} Danilo Miguel. Todos os direitos reservados. Feito com ❤️ para os amantes da culinária.</p>
            </div>
        </div>
    </main>

    <script>
        // Chave única para localStorage baseada no ID da receita
        const STORAGE_KEY = 'recipe_ingredients_{{ $recipe->id }}';
        
        // Carrega estado salvo ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            loadIngredientsState();
        });
        
        // Função para alternar estado do ingrediente
        function toggleIngredient(index) {
            const checkbox = document.querySelector(`.ingredient-item:nth-child(${index + 1}) .ingredient-checkbox`);
            const content = document.getElementById(`ingredient-${index}`);
            
            // Alterna o estado visual
            checkbox.classList.toggle('checked');
            content.classList.toggle('checked');
            
            // Salva o estado no localStorage
            saveIngredientsState();
        }
        
        // Função para salvar estado no localStorage
        function saveIngredientsState() {
            const checkedIngredients = [];
            const checkboxes = document.querySelectorAll('.ingredient-checkbox');
            
            checkboxes.forEach((checkbox, index) => {
                if (checkbox.classList.contains('checked')) {
                    checkedIngredients.push(index);
                }
            });
            
            localStorage.setItem(STORAGE_KEY, JSON.stringify(checkedIngredients));
        }
        
        // Função para carregar estado do localStorage
        function loadIngredientsState() {
            const savedState = localStorage.getItem(STORAGE_KEY);
            
            if (savedState) {
                const checkedIngredients = JSON.parse(savedState);
                
                checkedIngredients.forEach(index => {
                    const checkbox = document.querySelector(`.ingredient-item:nth-child(${index + 1}) .ingredient-checkbox`);
                    const content = document.getElementById(`ingredient-${index}`);
                    
                    if (checkbox && content) {
                        checkbox.classList.add('checked');
                        content.classList.add('checked');
                    }
                });
            }
        }
        
        // Função para limpar todas as marcações
        function clearAllIngredients() {
            const checkboxes = document.querySelectorAll('.ingredient-checkbox');
            const contents = document.querySelectorAll('.ingredient-content');
            
            // Remove classes visuais
            checkboxes.forEach(checkbox => {
                checkbox.classList.remove('checked');
            });
            
            contents.forEach(content => {
                content.classList.remove('checked');
            });
            
            // Limpa localStorage
            localStorage.removeItem(STORAGE_KEY);
        }
    </script>
</body>
</html>