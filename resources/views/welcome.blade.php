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
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background */
        .animated-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .floating-shape {
            position: absolute;
            pointer-events: none;
            opacity: 0.15;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            animation-direction: alternate;
        }
        
        .shape-1 {
            width: 500px;
            height: 140px;
            background: linear-gradient(45deg, rgba(200, 220, 240, 0.25), rgba(150, 180, 220, 0.15));
            top: 15%;
            left: -10%;
            border-radius: 60% 40% 70% 30%;
            opacity: 0.18;
            animation: waveFloat1 20s infinite, waveMove1 18s linear infinite;
        }
        
        .shape-2 {
            width: 420px;
            height: 100px;
            background: linear-gradient(-45deg, rgba(100, 150, 250, 0.22), rgba(80, 120, 200, 0.12));
            top: 45%;
            right: -15%;
            border-radius: 50% 30% 80% 20%;
            opacity: 0.16;
            animation: waveFloat2 24s infinite, waveMove2 20s linear infinite;
        }
        
        .shape-3 {
            width: 380px;
            height: 120px;
            background: linear-gradient(90deg, rgba(220, 235, 255, 0.2), rgba(180, 210, 240, 0.1));
            top: 70%;
            left: 20%;
            border-radius: 40% 60% 50% 70%;
            opacity: 0.14;
            animation: waveFloat3 18s infinite, waveMove3 16s linear infinite;
        }
        
        .shape-4 {
            width: 550px;
            height: 110px;
            background: linear-gradient(135deg, rgba(80, 100, 130, 0.18), rgba(60, 80, 110, 0.08));
            bottom: 25%;
            right: 10%;
            border-radius: 70% 30% 60% 40%;
            opacity: 0.12;
            animation: waveFloat4 22s infinite, waveMove4 19s linear infinite;
        }
        
        .shape-5 {
            width: 350px;
            height: 90px;
            background: linear-gradient(180deg, rgba(70, 90, 120, 0.2), rgba(50, 70, 100, 0.1));
            top: 5%;
            left: 50%;
            border-radius: 80% 20% 40% 60%;
            opacity: 0.15;
            animation: waveFloat5 17s infinite, waveMove5 14s linear infinite;
        }
        
        .shape-6 {
            width: 480px;
            height: 130px;
            background: linear-gradient(270deg, rgba(90, 110, 140, 0.17), rgba(70, 90, 120, 0.07));
            bottom: 10%;
            left: -5%;
            border-radius: 30% 70% 80% 20%;
            opacity: 0.13;
            animation: waveFloat6 25s infinite, waveMove6 21s linear infinite;
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
        @media (max-width: 1024px) {
            .about-content {
                grid-template-columns: 1.2fr 0.8fr;
                gap: 3rem;
            }
            
            .about-text h2 {
                font-size: 2.2rem;
            }
            
            .about-text p {
                font-size: 1rem;
            }
            
            .animated-avatar {
                width: 350px;
                height: 350px;
            }
        }
        
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
        
        /* Responsive Animated Background - Waves */
        @media (max-width: 768px) {
            .shape-1 {
                width: 180px;
                height: 45px;
            }
            
            .shape-2 {
                width: 120px;
                height: 30px;
            }
            
            .shape-3 {
                width: 200px;
                height: 50px;
            }
            
            .shape-4 {
                width: 160px;
                height: 40px;
            }
            
            .shape-5 {
                width: 140px;
                height: 35px;
            }
            
            .shape-6 {
                width: 190px;
                height: 55px;
            }
        }
        
        @media (max-width: 480px) {
            .floating-shape {
                opacity: 0.08;
            }
            
            .shape-1 {
                width: 120px;
                height: 30px;
            }
            
            .shape-2 {
                width: 80px;
                height: 20px;
            }
            
            .shape-3 {
                width: 140px;
                height: 35px;
            }
            
            .shape-4 {
                width: 100px;
                height: 25px;
            }
            
            .shape-5 {
                display: none; /* Hide on very small screens */
            }
            
            .shape-6 {
                width: 130px;
                height: 40px;
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
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .contact-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateX(5px);
        }
        
        .contact-item i {
            font-size: 1.5rem;
            margin-right: 1rem;
            width: 30px;
            transition: transform 0.3s ease;
        }
        
        .contact-item:hover i {
            transform: scale(1.1);
        }
        
        .contact-icon-email {
            color: #3b82f6;
        }
        
        .contact-icon-whatsapp {
            color: #10b981;
        }
        
        .contact-icon-location {
            color: #ef4444;
        }
        
        .contact-icon-behance {
            color: #8b5cf6;
        }
        
        .contact-link {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .contact-link:hover {
            color: #3b82f6;
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
        
        /* Wave Animations */
        @keyframes waveFloat1 {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
            100% { transform: translateY(0px) scale(1); }
        }
        
        @keyframes waveMove1 {
            0% { transform: translateX(-10%); }
            100% { transform: translateX(110%); }
        }
        
        @keyframes waveFloat2 {
            0% { transform: translateY(0px) scale(1); }
            33% { transform: translateY(12px) scale(0.95); }
            66% { transform: translateY(-8px) scale(1.03); }
            100% { transform: translateY(0px) scale(1); }
        }
        
        @keyframes waveMove2 {
            0% { transform: translateX(15%); }
            100% { transform: translateX(-115%); }
        }
        
        @keyframes waveFloat3 {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(18px) scale(1.02); }
            100% { transform: translateY(0px) scale(1); }
        }
        
        @keyframes waveMove3 {
            0% { transform: translateX(20%); }
            100% { transform: translateX(-120%); }
        }
        
        @keyframes waveFloat4 {
            0% { transform: translateY(0px) scale(1); }
            40% { transform: translateY(-10px) scale(1.04); }
            80% { transform: translateY(5px) scale(0.98); }
            100% { transform: translateY(0px) scale(1); }
        }
        
        @keyframes waveMove4 {
            0% { transform: translateX(10%); }
            100% { transform: translateX(-110%); }
        }
        
        @keyframes waveFloat5 {
            0% { transform: translateY(0px) scale(1); }
            30% { transform: translateY(8px) scale(1.01); }
            70% { transform: translateY(-12px) scale(0.99); }
            100% { transform: translateY(0px) scale(1); }
        }
        
        @keyframes waveMove5 {
            0% { transform: translateX(50%); }
            100% { transform: translateX(-150%); }
        }
        
        @keyframes waveFloat6 {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.03); }
            100% { transform: translateY(0px) scale(1); }
        }
        
        @keyframes waveMove6 {
            0% { transform: translateX(-5%); }
            100% { transform: translateX(105%); }
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
                 grid-template-columns: repeat(1, 1fr) !important;
                 gap: 1.5rem;
             }
             
             #portfolio h2 {
                 font-size: 1.75rem;
             }
             
             .portfolio-item {
                 max-width: 400px;
                 margin: 0 auto;
             }
         }
         
         @media (max-width: 640px) {
             #portfolio-grid {
                 grid-template-columns: repeat(1, 1fr) !important;
                 gap: 1rem;
             }
             
             .portfolio-item {
                 max-width: 100%;
                 margin: 0 auto;
             }
             
             .portfolio-filter {
                 flex-wrap: wrap;
                 gap: 1rem;
                 margin-bottom: 2rem;
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
             background: #f1f5f9;
             color: #475569;
             border: 1px solid #e2e8f0;
             padding: 4px 12px;
             border-radius: 6px;
             font-size: 13px;
             font-weight: 500;
             transition: all 0.3s ease;
         }
         
         .modal-tech-tag:hover {
             background: #e2e8f0;
             color: #334155;
             border-color: #cbd5e1;
         }
         
         /* New Modal Styles */
         .modal-short-description {
             font-size: 16px;
             line-height: 1.6;
             color: #666;
             margin: 15px 0;
             text-align: left;
         }
         
         .modal-full-content {
             padding: 30px 40px;
             border-bottom: 1px solid #e5e5e5;
         }
         
         .modal-content-title {
             font-size: 14px;
             font-weight: 600;
             color: #666;
             text-transform: uppercase;
             letter-spacing: 0.5px;
             margin-bottom: 15px;
         }
         
         .modal-download-section {
             padding: 30px 40px;
             text-align: center;
             border-bottom: 1px solid #e5e5e5;
         }
         
         .modal-download-btn {
             display: inline-flex;
             align-items: center;
             gap: 10px;
             background: #3b82f6;
             color: white;
             padding: 12px 24px;
             border-radius: 8px;
             text-decoration: none;
             font-weight: 500;
             transition: background-color 0.3s ease;
         }
         
         .modal-download-btn:hover {
             background: #1d4ed8;
         }
         
         .modal-download-btn svg {
             width: 20px;
             height: 20px;
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
                    <a href="{{ $user->website_url }}" target="_blank" class="social-link" title="BEHANCE" style="color: #1769ff;">
                        <i class="fab fa-behance"></i>
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
        <!-- Animated Background -->
        <div class="animated-background">
            <div class="floating-shape shape-1"></div>
            <div class="floating-shape shape-2"></div>
            <div class="floating-shape shape-3"></div>
            <div class="floating-shape shape-4"></div>
            <div class="floating-shape shape-5"></div>
            <div class="floating-shape shape-6"></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <h1>Design e ilustração para comunicar, inspirar e transformar.</h1>
                <p>Cada traço, cor e forma é pensado para transformar mensagens em experiências marcantes.</p>
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
                        <video src="{{ asset('storage/caricatura.mp4') }}" alt="Avatar Animado" class="avatar-img active" autoplay loop muted playsinline></video>
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
                <button class="category-filter" data-category="{{ $category->slug }}">{{ mb_strtoupper($category->name, 'UTF-8') }}</button>
                @endforeach
            </div>
            @endif
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="portfolio-grid">
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
                     data-content="{{ $work->content ?? '' }}"
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
                        <i class="fas fa-envelope contact-icon-email"></i>
                        <span>danilo.a.miguel@hotmail.com</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fab fa-whatsapp contact-icon-whatsapp"></i>
                        <a href="https://wa.me/5514991436268" target="_blank" class="contact-link">
                            <span>(14) 99143-6268</span>
                        </a>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon-location"></i>
                        <span>Marília, SP - Brasil</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fab fa-behance contact-icon-behance"></i>
                        <a href="https://www.behance.net/danilomiguel" target="_blank" class="contact-link">
                            <span>behance.net/danilomiguel</span>
                        </a>
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
        console.log('Portfolio modal JavaScript loaded successfully');
        let currentImageIndex = 0;
        let portfolioImages = [];
        let currentWorkData = {};
        
        // Test function accessibility
        window.testPortfolioModal = function() {
            console.log('Portfolio modal functions are accessible');
            console.log('openPortfolioModal function:', typeof openPortfolioModal);
        };
        
        function openPortfolioModal(element) {
            console.log('openPortfolioModal called with element:', element);
            
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
            const content = element.getAttribute('data-content');
            
            console.log('Work ID:', workId);
            console.log('Work data:', { title, description, categoryName, client, date, url, technologies, content });
            
            // Store current work data
            currentWorkData = {
                workId, title, description, categoryName, client, date, url, technologies, content
            };
            
            // Fetch portfolio images
            fetchPortfolioImages(workId).then(images => {
                console.log('Images fetched successfully:', images);
                portfolioImages = images;
                currentImageIndex = 0;
                updateModalContent();
                showModal();
            }).catch(error => {
                console.error('Error in fetchPortfolioImages promise:', error);
            });
        }
        
        async function fetchPortfolioImages(workId) {
            try {
                const apiUrl = `/api/portfolio/works/${workId}/images`;
                console.log('Fetching images from:', apiUrl);
                
                const response = await fetch(apiUrl);
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`Failed to fetch images: ${response.status} ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('API Response data:', data);
                
                // Transform the images data to include proper URLs
                const transformedImages = data.images.map(image => ({
                    url: `/storage/${image.path}`,
                    thumbnail_url: `/storage/${image.path}`,
                    alt_text: image.alt_text || currentWorkData.title,
                    caption: image.caption || ''
                }));
                
                console.log('Transformed images:', transformedImages);
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
            
            // Client section removed - no longer needed
            
            // Update date if exists
            const dateElement = document.getElementById('modalDate');
            const dateSection = document.getElementById('dateSection');
            if (currentWorkData.date && currentWorkData.date.trim() !== '') {
                dateElement.textContent = currentWorkData.date;
                dateSection.style.display = 'block';
            } else {
                dateSection.style.display = 'none';
            }
            
            // Update URL for download button if exists
            const urlElement = document.getElementById('modalUrl');
            const downloadSection = document.getElementById('downloadSection');
            if (currentWorkData.url && currentWorkData.url.trim() !== '') {
                urlElement.href = currentWorkData.url;
                downloadSection.style.display = 'block';
            } else {
                downloadSection.style.display = 'none';
            }
            
            // Update full content section
            const contentElement = document.getElementById('modalContent');
            const fullContentSection = document.getElementById('fullContentSection');
            if (currentWorkData.content && currentWorkData.content.trim() !== '') {
                contentElement.innerHTML = currentWorkData.content;
                fullContentSection.style.display = 'block';
            } else {
                fullContentSection.style.display = 'none';
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
                        
                        <!-- Header: Título, Categoria e Descrição Curta -->
                        <div class="modal-header">
                            <h1 id="modalTitle" class="modal-title"></h1>
                            <span id="modalCategory" class="modal-category"></span>
                            <p id="modalDescription" class="modal-short-description"></p>
                            
                            <!-- Tecnologias no Header -->
                            <div id="technologiesSection" class="modal-detail-item">
                                <div id="modalTechnologies" class="modal-tech-tags"></div>
                            </div>
                        </div>
                        
                        <!-- Images Stacked Vertically -->
                        <div id="modalImagesContainer" class="modal-images-stack">
                            <!-- Images will be populated by JavaScript -->
                        </div>
                        
                        <!-- Conteúdo Completo - Depois das Imagens -->
                        <div id="fullContentSection" class="modal-full-content">
                            <h3 class="modal-content-title">Conteúdo Completo</h3>
                            <div id="modalContent" class="modal-content-text"></div>
                        </div>
                        
                        <!-- Botão de Download Centralizado -->
                        <div id="downloadSection" class="modal-download-section">
                            <a id="modalUrl" href="#" target="_blank" class="modal-download-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Acessar Projeto
                            </a>
                        </div>
                        
                        <!-- Project Details - Apenas Data -->
                        <div class="modal-info">
                            <div class="modal-details">
                                <div id="dateSection" class="modal-detail-item">
                                    <span class="modal-detail-label">Ano:</span>
                                    <span id="modalDate" class="modal-detail-value"></span>
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
