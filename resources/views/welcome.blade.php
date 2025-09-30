<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portfolio') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #2b363f;
            --primary-orange: #f8aa22;
            --primary-white: #fff;
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
        }
        
        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(43, 54, 63, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }
        
        .logo img {
            height: 50px;
            width: auto;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-link {
            color: var(--primary-white);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-orange);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-orange);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .social-icons {
            display: flex;
            gap: 0.8rem;
            align-items: center;
        }
        
        .social-link {
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .social-link:hover {
            transform: scale(1.2);
            opacity: 0.8;
        }
        
        .auth-links {
            display: flex;
            gap: 1rem;
        }
        
        .auth-link {
            color: var(--primary-white);
            font-size: 1.2rem;
            transition: color 0.3s ease;
            text-decoration: none;
        }
        
        .auth-link:hover {
            color: var(--primary-orange);
        }
        
        /* Sections */
        section {
            min-height: 100vh;
            padding: 8rem 2rem 2rem;
            position: relative;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Home Section */
        #home {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #3a4a56 100%);
            color: var(--primary-white);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            opacity: 0;
            animation: fadeInUp 1s ease 0.5s forwards;
        }
        
        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeInUp 1s ease 0.7s forwards;
        }
        
        .cta-button {
            background: var(--primary-orange);
            color: var(--primary-white);
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeInUp 1s ease 0.9s forwards;
        }
        
        .cta-button:hover {
            background: #e6991f;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(248, 170, 34, 0.3);
        }
        
        /* About Section */
        #about {
            background: var(--primary-white);
            color: var(--primary-dark);
            padding-top: 10rem;
        }
        
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .about-text h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
        }
        
        .about-text p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }
        
        .about-image {
            text-align: center;
        }
        
        .about-image img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(43, 54, 63, 0.1);
        }
        
        /* Animated Avatar Styles */
        .animated-avatar {
            position: relative;
            width: 400px;
            height: 400px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 50%;
        }
        
        .avatar-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            border-radius: 50%;
        }
        
        .avatar-img.active {
            opacity: 1;
        }
        
        /* Responsive styles for animated avatar */
        @media (max-width: 768px) {
            .about-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }
            
            .animated-avatar {
                width: 350px;
                height: 350px;
                margin: 1rem auto;
            }
            
            .about-text {
                order: 2;
            }
            
            .about-image {
                order: 1;
            }
        }
        
        @media (max-width: 480px) {
            .animated-avatar {
                width: 300px;
                height: 300px;
            }
        }
        
        /* Portfolio Section */
        #portfolio {
            background: #f8f9fa;
            color: var(--primary-dark);
            padding-top: 10rem;
            min-height: 100vh;
        }
        
        #portfolio .container {
            max-width: 1200px;
        }
        
        #portfolio h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            text-align: center;
        }
        
        #portfolio .text-lg {
            font-size: 1.125rem;
            color: #6b7280;
            line-height: 1.6;
            text-align: center;
        }
        
        /* Category Filter Buttons - Header Menu Style */
        .portfolio-filter {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
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
        
        /* Portfolio Grid Items */
        .portfolio-item {
            transition: box-shadow 0.3s ease;
            cursor: pointer;
        }
        
        .portfolio-item:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        /* Melhorias adicionais para thumbnails */
        .portfolio-item:hover img {
            transform: scale(1.02);
            transition: transform 0.3s ease;
            filter: contrast(1.05) saturate(1.1) brightness(1.02);
        }
        
        /* Otimização para diferentes densidades de pixel */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .portfolio-item img {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: auto;
                filter: contrast(1.01) saturate(1.02);
            }
        }
        
        /* Portfolio Modal Styles */
         .portfolio-modal {
             backdrop-filter: blur(5px);
         }
         
         .portfolio-modal .modal-content {
             max-height: 90vh;
             overflow-y: auto;
         }
         
         /* Portfolio Item Overlay */
         .portfolio-item {
             position: relative;
         }
         
         .portfolio-item .overlay {
             position: absolute;
             top: 0;
             left: 0;
             right: 0;
             bottom: 0;
             background: rgba(0, 0, 0, 0.85);
             display: flex;
             flex-direction: column;
             justify-content: center;
             align-items: center;
             opacity: 0;
             transition: opacity 0.3s ease;
             z-index: 10;
         }
         
         .portfolio-item:hover .overlay {
             opacity: 1;
         }
         
         .portfolio-item .overlay h3 {
             color: white;
             font-size: 1.25rem;
             font-weight: 700;
             margin-bottom: 0.5rem;
             text-align: center;
             text-transform: uppercase;
             letter-spacing: 0.5px;
         }
         
         .portfolio-item .overlay p {
             color: white;
             font-size: 0.875rem;
             text-align: center;
             margin: 0;
             font-weight: 500;
         }
         
         /* Portfolio Item Image */
         .portfolio-item img {
             width: 100%;
             height: 100%;
             object-fit: cover;
             transition: none;
             /* Melhorias para qualidade de imagem */
             image-rendering: -webkit-optimize-contrast;
             image-rendering: crisp-edges;
             image-rendering: pixelated;
             image-rendering: auto;
             -webkit-backface-visibility: hidden;
             backface-visibility: hidden;
             -webkit-transform: translateZ(0);
             transform: translateZ(0);
             /* Anti-aliasing e suavização */
             -webkit-font-smoothing: antialiased;
             -moz-osx-font-smoothing: grayscale;
             /* Filtros para melhor qualidade */
             filter: contrast(1.02) saturate(1.05);
             /* Interpolação suave para redimensionamento */
             image-rendering: -moz-crisp-edges;
             image-rendering: -o-crisp-edges;
             image-rendering: -webkit-optimize-contrast;
             -ms-interpolation-mode: bicubic;
         }
        
        /* Contact Section */
        #contact {
            background: var(--primary-dark);
            color: var(--primary-white);
            padding-top: 10rem;
        }
        
        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
        }
        
        .contact-info h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary-orange);
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .contact-item i {
            font-size: 1.5rem;
            color: var(--primary-orange);
            margin-right: 1rem;
            width: 30px;
        }
        
        .contact-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-white);
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-dark);
        }
        
        .form-group textarea {
            height: 120px;
            resize: vertical;
        }
        
        .submit-btn {
            background: var(--primary-orange);
            color: var(--primary-white);
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .submit-btn:hover {
            background: #e6991f;
        }
        
        /* Wave Effects */
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        
        .wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }
        
        .wave .shape-fill {
            fill: var(--primary-white);
        }
        
        .wave.wave-orange .shape-fill {
            fill: #f8f9fa;
        }
        
        .wave.wave-dark .shape-fill {
            fill: var(--primary-dark);
        }
        
        .wave.wave-portfolio .shape-fill {
            fill: #f8f9fa;
        }
        
        .wave.wave-footer .shape-fill {
            fill: #1f212c;
        }
        
        /* Wave transition from contact to footer */
        .wave-contact-to-footer {
            position: relative;
            background: var(--primary-dark);
        }
        
        .wave-contact-to-footer .shape-fill {
            fill: #1f212c;
        }
        
        /* Footer Styles */
        .footer-section {
            background-color: #1f212c;
            color: white;
            padding: 2rem 0;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .footer-content {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-logo {
            flex-shrink: 0;
        }
        
        .fonteria-logo {
            width: 180px;
            height: auto;
            max-width: 180px;
        }
        
        .footer-text {
            flex: 1;
        }
        
        .footer-text p {
            color: #d1d5db;
            font-size: 0.875rem;
            line-height: 1.5;
            margin: 0;
        }
        
        .footer-link {
            color: #fb923c;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: #fdba74;
        }
        
        .footer-copyright {
            border-top: 1px solid #4b5563;
            padding-top: 1.5rem;
            text-align: center;
        }
        
        .footer-copyright p {
            color: #9ca3af;
            font-size: 0.75rem;
            margin: 0;
            line-height: 1.4;
        }
        
        /* Footer Responsive */
        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }
            
            .fonteria-logo {
                width: 100px;
                max-width: 100px;
            }
            
            .footer-text p {
                font-size: 0.8rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Portfolio Grid Styles */
         #portfolio-grid {
             display: grid;
             grid-template-columns: repeat(5, 1fr);
             gap: 1rem;
             margin-top: 2rem;
         }
         
         /* Portfolio Responsive Styles */
         @media (max-width: 1024px) {
             #portfolio-grid {
                 grid-template-columns: repeat(4, 1fr) !important;
             }
         }
         
         @media (max-width: 768px) {
             .nav-menu {
                 display: none;
             }
             
             .header-right {
                 gap: 1rem;
             }
             
             .social-icons {
                 gap: 0.5rem;
             }
             
             .social-link {
                 font-size: 1rem;
             }
             
             .hero-content h1 {
                 font-size: 2.5rem;
             }
             
             .about-content,
             .contact-content {
                 grid-template-columns: 1fr;
                 gap: 2rem;
             }
             
             section {
                 padding: 6rem 1rem 2rem;
             }
             
             #portfolio-grid {
                 grid-template-columns: repeat(3, 1fr) !important;
                 gap: 0.75rem !important;
             }
             
             .category-filter {
                 font-size: 0.875rem;
                 padding: 0.5rem 1rem;
             }
             
             #portfolio h2 {
                 font-size: 2rem;
             }
         }
         
         @media (max-width: 480px) {
             #portfolio-grid {
                 grid-template-columns: repeat(2, 1fr) !important;
             }
             
             #portfolio h2 {
                 font-size: 1.75rem;
             }
         }
         
         /* Portfolio Modal Styles */
         .portfolio-modal {
             position: fixed;
             top: 0;
             left: 0;
             width: 100vw;
             height: 100vh;
             background: rgba(0, 0, 0, 0.8);
             z-index: 9999;
             display: flex;
             align-items: flex-start;
             justify-content: center;
             padding: 20px;
             box-sizing: border-box;
             overflow-y: auto;
             opacity: 0;
             visibility: hidden;
             transition: all 0.3s ease;
         }
         
         .portfolio-modal:not(.hidden) {
             opacity: 1;
             visibility: visible;
         }
         
         .modal-content-white {
             background: white;
             max-width: 800px;
             width: 100%;
             margin: 20px auto;
             position: relative;
             box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
             border-radius: 0;
         }
         
         .modal-close-btn-white {
             position: fixed;
             top: 30px;
             right: 30px;
             background: rgba(0, 0, 0, 0.7);
             border: none;
             color: white;
             width: 40px;
             height: 40px;
             border-radius: 50%;
             display: flex;
             align-items: center;
             justify-content: center;
             cursor: pointer;
             transition: all 0.3s ease;
             z-index: 10001;
         }
         
         .modal-close-btn-white:hover {
             background: rgba(0, 0, 0, 0.9);
             transform: scale(1.1);
         }
         
         .modal-header {
             padding: 40px 40px 20px 40px;
             border-bottom: 1px solid #e5e5e5;
         }
         
         .modal-title {
             font-size: 32px;
             font-weight: 700;
             margin-bottom: 8px;
             color: #1a1a1a;
             line-height: 1.2;
         }
         
         .modal-category {
             color: #3b82f6;
             font-size: 14px;
             font-weight: 600;
             text-transform: uppercase;
             letter-spacing: 1px;
         }
         
         .modal-images-stack {
             display: block;
         }
         
         .modal-stacked-image {
             width: 100%;
             height: auto;
             display: block;
             margin: 0;
             border: none;
         }
         
         .modal-image-wrapper {
             margin: 0;
         }
         
         .modal-image-caption {
             padding: 15px 40px;
             margin: 0;
             font-size: 14px;
             color: #666;
             font-style: italic;
             background: #f8f9fa;
             border-bottom: 1px solid #e5e5e5;
         }
         
         .modal-info {
             padding: 40px;
         }
         
         .modal-description p {
             font-size: 18px;
             line-height: 1.6;
             margin-bottom: 25px;
             color: #333;
         }
         
         .modal-content-text {
             font-size: 16px;
             line-height: 1.7;
             margin-bottom: 25px;
             color: #555;
         }
         
         .modal-detail-item {
             display: block;
             margin-bottom: 20px;
             font-size: 16px;
         }
         
         .modal-detail-label {
             font-weight: 600;
             margin-bottom: 5px;
             color: #3b82f6;
             display: block;
         }
         
         .modal-detail-value {
             color: #333;
             display: block;
         }
         
         .modal-detail-link {
             color: #3b82f6;
             text-decoration: none;
             transition: color 0.3s ease;
         }
         
         .modal-detail-link:hover {
             color: #1d4ed8;
             text-decoration: underline;
         }
         
         .modal-tech-tags {
             display: flex;
             flex-wrap: wrap;
             gap: 10px;
         }
         
         .modal-tech-tag {
             background: #3b82f6;
             color: white;
             padding: 8px 16px;
             border-radius: 25px;
             font-size: 14px;
             font-weight: 500;
             transition: background 0.3s ease;
         }
         
         .modal-tech-tag:hover {
             background: #1d4ed8;
         }
         

         
         /* Mobile Responsive */
         @media (max-width: 768px) {
             .portfolio-modal {
                 padding: 10px;
             }
             
             .modal-content-white {
                 margin: 10px auto;
                 max-width: 100%;
             }
             
             .modal-close-btn-white {
                 top: 20px;
                 right: 20px;
                 width: 35px;
                 height: 35px;
             }
             
             .modal-header {
                 padding: 30px 20px 15px 20px;
             }
             
             .modal-title {
                 font-size: 24px;
             }
             
             .modal-image-caption {
                 padding: 10px 20px;
             }
             
             .modal-info {
                 padding: 30px 20px;
             }
             
             .modal-description p {
                 font-size: 16px;
             }
             
             .modal-tech-tags {
                 gap: 8px;
             }
             
             .modal-tech-tag {
                 padding: 6px 12px;
                 font-size: 13px;
             }
         }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="{{ asset('storage/logo_site.svg') }}" alt="Logo Site" style="height: 50px; width: auto; display: block;">
            </div>
            
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#about" class="nav-link">Sobre</a></li>
                <li><a href="#portfolio" class="nav-link">Portfólio</a></li>
                <li><a href="#contact" class="nav-link">Contato</a></li>
            </ul>
            
            <div class="header-right">
                <!-- Social Media Icons -->
                @php
                    $user = App\Models\User::find(1);
                @endphp
                @if($user && ($user->facebook_url || $user->instagram_url || $user->twitter_url || $user->linkedin_url || $user->youtube_url || $user->tiktok_url || $user->whatsapp_url || $user->website_url))
                <div class="social-icons">
                    @if($user->facebook_url)
                    <a href="{{ $user->facebook_url }}" target="_blank" class="social-link" title="Facebook" style="color: #1877F2;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if($user->instagram_url)
                    <a href="{{ $user->instagram_url }}" target="_blank" class="social-link" title="Instagram" style="color: #E4405F;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    @if($user->twitter_url)
                    <a href="{{ $user->twitter_url }}" target="_blank" class="social-link" title="Twitter" style="color: #1DA1F2;">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif
                    @if($user->linkedin_url)
                    <a href="{{ $user->linkedin_url }}" target="_blank" class="social-link" title="LinkedIn" style="color: #0A66C2;">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    @endif
                    @if($user->youtube_url)
                    <a href="{{ $user->youtube_url }}" target="_blank" class="social-link" title="YouTube" style="color: #FF0000;">
                        <i class="fab fa-youtube"></i>
                    </a>
                    @endif
                    @if($user->tiktok_url)
                    <a href="{{ $user->tiktok_url }}" target="_blank" class="social-link" title="TikTok" style="color: #000000;">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    @endif
                    @if($user->whatsapp_url)
                    <a href="{{ $user->whatsapp_url }}" target="_blank" class="social-link" title="WhatsApp" style="color: #25D366;">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    @endif
                    @if($user->website_url)
                    <a href="{{ $user->website_url }}" target="_blank" class="social-link" title="Website" style="color: #2b363f;">
                        <i class="fas fa-globe"></i>
                    </a>
                    @endif
                </div>
                @endif
                
                <!-- Auth Links -->
                @if (Route::has('login'))
                    <div class="auth-links">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="auth-link" title="Dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="auth-link" title="Entrar">
                                <i class="fas fa-sign-in-alt"></i>
                            </a>
                            @if (Route::has('register') && App\Models\Setting::isPublicRegistrationEnabled())
                                <a href="{{ route('register') }}" class="auth-link" title="Cadastrar">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>



    <!-- Home Section -->
    <section id="home">
        <div class="container">
            <div class="hero-content">
                <h1>Bem-vindo ao Nosso Portfólio</h1>
                <p>Criamos experiências digitais incríveis que conectam marcas e pessoas</p>
                <a href="#portfolio" class="cta-button">Ver Trabalhos</a>
            </div>
        </div>
        
        <!-- Wave Transition -->
        <div class="wave">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Sobre mim</h2>
                    <p>Desde a infância, sempre tive uma forte conexão com o desenho, e ao longo dos anos fui aprimorando minhas habilidades em diferentes técnicas artísticas. Trabalhei durante nove anos como cartazista no comércio local, criando cartazes, faixas e banners que ajudavam lojas e estabelecimentos a se comunicarem de forma criativa com seus clientes.</p>
                    <p>Em 2012, iniciei a graduação em Design Gráfico, o que ampliou meu repertório criativo e técnico. Foi nessa fase que mergulhei em áreas como diagramação, identidade visual, animação e ilustração digital, consolidando minha paixão pela comunicação visual clara e eficiente.</p>
                    <p>Hoje, atuo como designer gráfico e ilustrador, com foco em livros infantis e jogos educativos. A maioria dos meus projetos é desenvolvida para apoiar o trabalho de fonoaudiólogos e psicopedagogos, tornando o aprendizado mais lúdico e envolvente para crianças de várias idades.</p>
                    <p>Meu objetivo é unir criatividade, funcionalidade e simplicidade, criando soluções visuais que encantam, comunicam e contribuem para experiências de aprendizagem significativas.</p>
                </div>
                <div class="about-image">
                    <div class="animated-avatar">
                        <img src="{{ asset('storage/caricaturas/caricatura_1.png') }}" alt="Avatar Animado" class="avatar-img active">
                        <img src="{{ asset('storage/caricaturas/caricatura_2.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_3.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_4.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_5.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_6.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_7.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_8.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_9.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_10.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_11.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_12.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_13.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_14.png') }}" alt="Avatar Animado" class="avatar-img">
                        <img src="{{ asset('storage/caricaturas/caricatura_15.png') }}" alt="Avatar Animado" class="avatar-img">
                    </div>
                </div>
            </div>
        </div>
        
                <br><br>
        <!-- Wave Transition -->
        <div class="wave wave-portfolio">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio">
        <div class="container mx-auto px-4 py-16">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4 text-center">Nosso Portfólio</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto text-center">Conheça alguns dos nossos trabalhos mais recentes</p>
            </div>
            
            <!-- Category Filter -->
            @if($portfolioCategories->count() > 0)
            <div class="portfolio-filter">
                <button class="category-filter active" data-category="all">TODOS</button>
                @foreach($portfolioCategories as $category)
                <button class="category-filter" data-category="{{ $category->slug }}">{{ strtoupper($category->name) }}</button>
                @endforeach
            </div>
            @endif
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="portfolio-grid">
                @forelse($portfolioWorks as $work)
                <div class="portfolio-item group relative aspect-square overflow-hidden rounded-lg cursor-pointer" 
                     data-category="{{ $work->category ? $work->category->slug : 'uncategorized' }}"
                     data-id="{{ $work->id }}"
                     data-title="{{ $work->title }}"
                     data-description="{{ $work->description }}"
                     data-category-name="{{ $work->category ? $work->category->name : 'Sem categoria' }}"
                     data-client="{{ $work->client ?? '' }}"
                     data-date="{{ $work->date ? $work->date->format('Y') : '' }}"
                     data-url="{{ $work->url ?? '' }}"
                     data-technologies="{{ $work->technologies ? implode(',', $work->technologies) : '' }}"
                     onclick="openPortfolioModal(this)">
                    
                    @if($work->featuredImage)
                        <img src="{{ $work->featuredImage->url }}" alt="{{ $work->title }}" class="w-full h-full object-cover">
                    @elseif($work->images->count() > 0)
                        <img src="{{ $work->images->first()->url }}" alt="{{ $work->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Hover Overlay -->
                    <div class="overlay">
                        <p class="text-sm font-medium mb-1">{{ $work->category ? $work->category->name : 'Sem categoria' }}</p>
                        <h3 class="text-lg font-bold">{{ $work->title }}</h3>
                    </div>
                </div>
                @empty
                <!-- Placeholder items when no portfolio works exist -->
                @for($i = 1; $i <= 10; $i++)
                <div class="portfolio-item group relative aspect-square overflow-hidden rounded-lg cursor-pointer" 
                     data-category="placeholder">
                    <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    
                    <!-- Hover Overlay -->
                    <div class="overlay">
                        <p class="text-sm font-medium mb-1">Em breve</p>
                        <h3 class="text-lg font-bold">Projeto {{ $i }}</h3>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </div>
        
        <!-- Wave Transition -->
        <div class="wave wave-dark">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2>Entre em Contato</h2>
                    <p>Vamos conversar sobre seu próximo projeto!</p>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contato@portfolio.com</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+55 (11) 99999-9999</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>São Paulo, SP - Brasil</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fab fa-linkedin"></i>
                        <span>linkedin.com/company/portfolio</span>
                    </div>
                </div>
                
                <div class="contact-form">
                    <!-- Mensagens de sucesso e erro -->
                    @if(session('success'))
                        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                            <strong>Sucesso!</strong> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <strong>Erro!</strong> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <strong>Por favor, corrija os seguintes erros:</strong>
                            <ul style="margin: 10px 0 0 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('public.contact.store') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="error-text" style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error-text" style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Assunto</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <span class="error-text" style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Mensagem</label>
                            <textarea id="message" name="message" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="error-text" style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="submit-btn" id="submitBtn">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // CTA button smooth scroll
        document.querySelector('.cta-button').addEventListener('click', function(e) {
            e.preventDefault();
            const targetSection = document.querySelector('#portfolio');
            targetSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
        
        // Active navigation link on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');
            
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (window.pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(43, 54, 63, 0.98)';
            } else {
                navbar.style.background = 'rgba(43, 54, 63, 0.95)';
            }
        });
        
        // Category filter functionality
        document.querySelectorAll('.category-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.category-filter').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('text-orange-500', 'border-b-2', 'border-orange-500');
                    btn.classList.add('text-gray-600');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                this.classList.remove('text-gray-600');
                this.classList.add('text-orange-500', 'border-b-2', 'border-orange-500');
                
                const category = this.getAttribute('data-category');
                const portfolioItems = document.querySelectorAll('.portfolio-item');
                
                portfolioItems.forEach(item => {
                    if (category === 'all' || item.getAttribute('data-category') === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Portfolio modal functionality
        let currentImageIndex = 0;
        let portfolioImages = [];
        let currentWorkData = {};
        
        function openPortfolioModal(element) {
            const modal = document.getElementById('portfolioModal');
            if (!modal) {
                createPortfolioModal();
            }
            
            const workId = element.getAttribute('data-id');
            const title = element.getAttribute('data-title');
            const description = element.getAttribute('data-description');
            const categoryName = element.getAttribute('data-category-name');
            const client = element.getAttribute('data-client');
            const date = element.getAttribute('data-date');
            const url = element.getAttribute('data-url');
            const technologies = element.getAttribute('data-technologies');
            
            // Store current work data
            currentWorkData = {
                workId, title, description, categoryName, client, date, url, technologies
            };
            
            // Fetch portfolio images
            fetchPortfolioImages(workId).then(images => {
                portfolioImages = images;
                currentImageIndex = 0;
                updateModalContent();
                showModal();
            });
        }
        
        async function fetchPortfolioImages(workId) {
            try {
                const response = await fetch(`/api/portfolio/${workId}/images`);
                if (!response.ok) {
                    throw new Error('Failed to fetch images');
                }
                const data = await response.json();
                
                // Transform the images data to include proper URLs
                const transformedImages = data.images.map(image => ({
                    url: `/storage/${image.path}`,
                    thumbnail_url: `/storage/${image.path}`,
                    alt_text: image.alt_text || currentWorkData.title,
                    caption: image.caption || ''
                }));
                
                return transformedImages;
            } catch (error) {
                console.error('Error fetching portfolio images:', error);
                // Fallback to featured image if API fails
                const featuredImage = document.querySelector(`[data-id="${workId}"] img`);
                if (featuredImage) {
                    return [{
                        url: featuredImage.src,
                        thumbnail_url: featuredImage.src,
                        alt_text: featuredImage.alt || currentWorkData.title,
                        caption: ''
                    }];
                }
                return [];
            }
        }
        
        function updateModalContent() {
            // Update project information
            document.getElementById('modalTitle').textContent = currentWorkData.title;
            document.getElementById('modalDescription').textContent = currentWorkData.description;
            document.getElementById('modalCategory').textContent = currentWorkData.categoryName;
            
            // Update client if exists
            const clientElement = document.getElementById('modalClient');
            const clientSection = document.getElementById('clientSection');
            if (currentWorkData.client && currentWorkData.client.trim() !== '') {
                clientElement.textContent = currentWorkData.client;
                clientSection.style.display = 'block';
            } else {
                clientSection.style.display = 'none';
            }
            
            // Update date if exists
            const dateElement = document.getElementById('modalDate');
            const dateSection = document.getElementById('dateSection');
            if (currentWorkData.date && currentWorkData.date.trim() !== '') {
                dateElement.textContent = currentWorkData.date;
                dateSection.style.display = 'block';
            } else {
                dateSection.style.display = 'none';
            }
            
            // Update URL if exists
            const urlElement = document.getElementById('modalUrl');
            const urlSection = document.getElementById('urlSection');
            if (currentWorkData.url && currentWorkData.url.trim() !== '') {
                urlElement.href = currentWorkData.url;
                urlSection.style.display = 'block';
            } else {
                urlSection.style.display = 'none';
            }
            
            // Update technologies if exists - Fix array display issue
            const techContainer = document.getElementById('modalTechnologies');
            const techSection = document.getElementById('technologiesSection');
            if (currentWorkData.technologies && currentWorkData.technologies.trim() !== '') {
                let techArray;
                
                // Check if technologies is already an array or a string
                if (typeof currentWorkData.technologies === 'string') {
                    // Remove array brackets if present and split by comma
                    const cleanTech = currentWorkData.technologies.replace(/[\[\]"]/g, '').trim();
                    techArray = cleanTech.split(',').map(tech => tech.trim()).filter(tech => tech !== '');
                } else if (Array.isArray(currentWorkData.technologies)) {
                    techArray = currentWorkData.technologies;
                } else {
                    techArray = [];
                }
                
                techContainer.innerHTML = '';
                techArray.forEach(tech => {
                    if (tech && tech.trim()) {
                        const techTag = document.createElement('span');
                        techTag.className = 'modal-tech-tag';
                        techTag.textContent = tech.trim();
                        techContainer.appendChild(techTag);
                    }
                });
                techSection.style.display = techArray.length > 0 ? 'block' : 'none';
            } else {
                techSection.style.display = 'none';
            }
            
            // Update images stack
            updateImagesStack();
        }
        
        function updateImagesStack() {
            const imagesContainer = document.getElementById('modalImagesContainer');
            imagesContainer.innerHTML = '';
            
            if (portfolioImages.length === 0) {
                imagesContainer.style.display = 'none';
                return;
            }
            
            imagesContainer.style.display = 'block';
            
            // Add all images stacked vertically
            portfolioImages.forEach((image, index) => {
                const imageElement = document.createElement('img');
                imageElement.src = image.url;
                imageElement.alt = image.alt_text || currentWorkData.title;
                imageElement.className = 'modal-stacked-image';
                
                // Add caption if exists
                if (image.caption && image.caption.trim()) {
                    const imageWrapper = document.createElement('div');
                    imageWrapper.className = 'modal-image-wrapper';
                    
                    const caption = document.createElement('p');
                    caption.className = 'modal-image-caption';
                    caption.textContent = image.caption;
                    
                    imageWrapper.appendChild(imageElement);
                    imageWrapper.appendChild(caption);
                    imagesContainer.appendChild(imageWrapper);
                } else {
                    imagesContainer.appendChild(imageElement);
                }
            });
        }
        
        function showModal() {
            const modal = document.getElementById('portfolioModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function createPortfolioModal() {
            const modalHTML = `
                <div id="portfolioModal" class="portfolio-modal hidden">
                    <!-- Modal Content -->
                    <div class="modal-content-white">
                        <!-- Close Button -->
                        <button onclick="closePortfolioModal()" class="modal-close-btn-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        
                        <!-- Project Title -->
                        <div class="modal-header">
                            <h1 id="modalTitle" class="modal-title"></h1>
                            <span id="modalCategory" class="modal-category"></span>
                        </div>
                        
                        <!-- Images Stacked Vertically -->
                        <div id="modalImagesContainer" class="modal-images-stack">
                            <!-- Images will be populated by JavaScript -->
                        </div>
                        
                        <!-- Project Information -->
                        <div class="modal-info">
                            <div class="modal-description">
                                <p id="modalDescription"></p>
                                <div id="modalContent" class="modal-content-text"></div>
                            </div>
                            
                            <div class="modal-details">
                                <div id="clientSection" class="modal-detail-item">
                                    <span class="modal-detail-label">Cliente:</span>
                                    <span id="modalClient" class="modal-detail-value"></span>
                                </div>
                                
                                <div id="dateSection" class="modal-detail-item">
                                    <span class="modal-detail-label">Ano:</span>
                                    <span id="modalDate" class="modal-detail-value"></span>
                                </div>
                                
                                <div id="urlSection" class="modal-detail-item">
                                    <span class="modal-detail-label">Website:</span>
                                    <a id="modalUrl" href="#" target="_blank" class="modal-detail-link">Visitar Site</a>
                                </div>
                                
                                <div id="technologiesSection" class="modal-detail-item">
                                    <span class="modal-detail-label">Tecnologias:</span>
                                    <div id="modalTechnologies" class="modal-tech-tags"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Add keyboard navigation
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('portfolioModal');
                if (!modal || modal.classList.contains('hidden')) return;
                
                switch(e.key) {
                    case 'Escape':
                        closePortfolioModal();
                        break;
                }
            });
        }
        
        function closePortfolioModal() {
            document.getElementById('portfolioModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('portfolioModal');
            if (modal && e.target === modal) {
                closePortfolioModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('portfolioModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closePortfolioModal();
                }
            }
        });
        
        // Contact form validation and submission
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    // Basic client-side validation
                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const subject = document.getElementById('subject').value.trim();
                    const message = document.getElementById('message').value.trim();
                    
                    if (!name || !email || !subject || !message) {
                        e.preventDefault();
                        alert('Por favor, preencha todos os campos obrigatórios.');
                        return;
                    }
                    
                    // Email validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        alert('Por favor, insira um email válido.');
                        return;
                    }
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Enviando...';
                    
                    // Form will submit normally after validation
                });
            }
        });
        
        // Animated Avatar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const avatarImages = document.querySelectorAll('.avatar-img');
            let currentIndex = 0;
            
            if (avatarImages.length > 0) {
                // Show first image initially
                avatarImages[0].classList.add('active');
                
                // Change image every 300ms for continuous animation
                setInterval(function() {
                    // Remove active class from current image
                    avatarImages[currentIndex].classList.remove('active');
                    
                    // Move to next image
                    currentIndex = (currentIndex + 1) % avatarImages.length;
                    
                    // Add active class to new image
                    avatarImages[currentIndex].classList.add('active');
                }, 300);
            }
        });
        
        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });
    </script>
    
    <!-- Wave Transition to Footer -->
    <div class="wave wave-contact-to-footer">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
        </svg>
    </div>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Fonteria Logo Column -->
                <div class="footer-logo">
                    <img src="{{ asset('storage/loja_fonteria.svg') }}" alt="Loja Fonteria" class="fonteria-logo">
                </div>
                
                <!-- Footer Text Column -->
                <div class="footer-text">
                    <p>
                        Visite a <a href="https://www.fonteria.com.br/" target="_blank" class="footer-link">FONTERIA.COM.BR</a>. Fonteria é uma pequena loja virtual para compartilhamento e venda de tipografias, imagens(fotografias) e gráficos, independentes e autorais.
                    </p>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="footer-copyright">
                <p>
                    © 2023 Danilo Miguel - Designer e Ilustrador. Todos os direitos reservados. Qualquer reprodução ou distribuição não autorizada destes materiais, no todo ou em parte, é estritamente proibida. As informações contidas neste site são apenas para fins informativos.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
