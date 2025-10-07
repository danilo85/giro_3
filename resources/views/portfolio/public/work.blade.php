@extends('layouts.app')

@section('title', $work->meta_title ?: $work->title . ' - Portfólio - ' . config('app.name'))
@section('description', $work->meta_description ?: Str::limit($work->description, 160))

@push('meta')
<!-- Open Graph -->
<meta property="og:title" content="{{ $work->meta_title ?: $work->title }}">
<meta property="og:description" content="{{ $work->meta_description ?: Str::limit($work->description, 160) }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ route('public.portfolio.public.work', $work->slug) }}">
@if($work->featured_image)
<meta property="og:image" content="{{ asset('storage/' . $work->featured_image) }}">
@endif

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $work->meta_title ?: $work->title }}">
<meta name="twitter:description" content="{{ $work->meta_description ?: Str::limit($work->description, 160) }}">
@if($work->featured_image)
<meta name="twitter:image" content="{{ asset('storage/' . $work->featured_image) }}">
@endif

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CreativeWork",
  "name": "{{ $work->title }}",
  "description": "{{ $work->description }}",
  "url": "{{ route('public.portfolio.public.work', $work->slug) }}",
  @if($work->featured_image)
  "image": "{{ asset('storage/' . $work->featured_image) }}",
  @endif
  "dateCreated": "{{ $work->created_at->toISOString() }}",
  "author": [
    @foreach($work->authors as $author)
    {
      "@type": "Person",
      "name": "{{ $author->name }}"
    }@if(!$loop->last),@endif
    @endforeach
  ],
  @if($work->category)
  "genre": "{{ $work->category->name }}",
  @endif
  "publisher": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}"
  }
}
</script>
@endpush

@section('content')
<div class="bg-white dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <!-- Back Button and Theme Toggle -->
    <div class="bg-white dark:bg-gray-900 py-4 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
            <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

        </div>
    </div>

    <!-- Modal-style Content Container -->
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Header Section -->
        <div class="px-10 py-8 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
            @if($work->category)
                <div class="mb-4">
                    <span class="text-blue-600 dark:text-blue-400 text-sm font-semibold uppercase tracking-wider">{{ $work->category->name }}</span>
                </div>
            @endif
            
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 leading-tight transition-colors duration-300">{{ $work->title }}</h1>
            
            <div class="flex flex-wrap items-center gap-6 text-gray-600 dark:text-gray-300 text-sm mb-6 transition-colors duration-300">
                @if($work->authors->count() > 0)
                    <div class="flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        <span>{{ $work->authors->pluck('name')->join(', ') }}</span>
                    </div>
                @endif
                
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>{{ $work->created_at->format('d/m/Y') }}</span>
                </div>
                
                @if($work->client)
                    <div class="flex items-center">
                        <i class="fas fa-building mr-2"></i>
                        <span>{{ is_object($work->client) ? $work->client->name : $work->client }}</span>
                    </div>
                @endif
            </div>
            
            <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed transition-colors duration-300">{{ $work->description }}</p>
            
            <!-- Metrics Section -->
            <div class="work-metrics mt-6 flex items-center gap-6">
                <div class="metric-item flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="work-views-count text-gray-600 dark:text-gray-300" data-work-id="{{ $work->id }}" data-work-slug="{{ $work->slug }}">{{ number_format($work->views_count ?? 0) }}</span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm">visualizações</span>
                </div>
                
                <div class="metric-item flex items-center gap-2">
                    <button class="work-like-btn flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-200" 
                            data-work-id="{{ $work->id }}" 
                            data-work-slug="{{ $work->slug }}"
                            onclick="toggleWorkLike('{{ $work->slug }}')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="work-likes-count" data-work-id="{{ $work->id }}" data-work-slug="{{ $work->slug }}">{{ number_format($work->likes_count ?? 0) }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Image Slideshow -->
        @php
            $allImages = collect();
            
            // Add featured image first
            if($work->featured_image) {
                $allImages->push((object)[
                    'path' => $work->featured_image,
                    'alt_text' => $work->title,
                    'is_featured' => true
                ]);
            }
            
            // Add gallery images
            foreach($work->images as $image) {
                $allImages->push((object)[
                    'path' => $image->path,
                    'alt_text' => $image->alt_text,
                    'is_featured' => false
                ]);
            }
        @endphp

        @if($allImages->count() > 0)
            <div class="work-slideshow-container" data-slideshow-id="work-main">
                <div class="work-slideshow">
                    @foreach($allImages as $index => $image)
                        <div class="work-slide {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                 alt="{{ $image->alt_text }}" 
                                 class="work-slide-image w-full h-auto display-block cursor-pointer"
                                 onclick="openLightbox('{{ asset('storage/' . $image->path) }}', '{{ $image->alt_text }}')">
                            @if($image->alt_text)
                                <div class="work-slide-caption">{{ $image->alt_text }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                @if($allImages->count() > 1)
                    <!-- Slideshow Indicators -->
                    <div class="work-slideshow-indicators">
                        @foreach($allImages as $index => $image)
                            <button class="work-indicator {{ $index === 0 ? 'active' : '' }}" 
                                    data-slide="{{ $index }}"
                                    aria-label="Ir para imagem {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Project Information -->
        <div class="modal-info px-10 py-8">
            @if($work->content)
                <div class="modal-content-text mb-8">
                    {!! $work->content !!}
                </div>
            @endif

            <!-- Project Details -->
            <div class="space-y-6">
                <!-- Technologies -->
                @if($work->technologies && is_array($work->technologies))
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Tecnologias:</span>
                        <div class="modal-tech-tags mt-2">
                            @foreach($work->technologies as $tech)
                                <span class="modal-tech-tag">{{ trim($tech) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Project URL -->
                @if($work->project_url)
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Link do Projeto:</span>
                        <a href="{{ $work->project_url }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="modal-detail-link">
                            Visitar site
                            <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                        </a>
                    </div>
                @endif
                
                <!-- Repository URL -->
                @if($work->repository_url)
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Repositório:</span>
                        <a href="{{ $work->repository_url }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="modal-detail-link">
                            <i class="fab fa-github mr-2"></i>
                            Ver código
                        </a>
                    </div>
                @endif
                

                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Related Works -->
@if($relatedWorks->count() > 0)
<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center transition-colors duration-300">Projetos Relacionados</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedWorks as $relatedWork)
                    <article class="bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="relative overflow-hidden">
                            @if($relatedWork->featured_image)
                                <img src="{{ asset('storage/' . $relatedWork->featured_image) }}" 
                                     alt="{{ $relatedWork->title }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <i class="fas fa-image text-3xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <a href="{{ route('public.portfolio.public.work', $relatedWork->slug) }}">
                                    {{ $relatedWork->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 transition-colors duration-300">{{ $relatedWork->description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif



<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-75 dark:bg-black dark:bg-opacity-85 hidden z-50 flex items-center justify-center p-4 transition-colors duration-300">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
        <div id="lightbox-caption" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 text-white p-4 text-center transition-colors duration-300"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Work Slideshow Styles */
.work-slideshow-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.work-slideshow {
    position: relative;
    width: 100%;
    height: auto;
}

.work-slide {
    display: none;
    width: 100%;
    position: relative;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.work-slide.active {
    display: block;
    opacity: 1;
}

.work-slide-image {
    width: 100%;
    height: auto;
    display: block;
    border: none;
    border-radius: 0;
    box-shadow: none;
}

.work-slide-caption {
    padding: 12px 40px;
    background-color: #f8f9fa;
    color: #6b7280;
    font-size: 14px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.dark .work-slide-caption {
    background-color: #374151;
    color: #d1d5db;
    border-bottom: 1px solid #4b5563;
}

.work-slideshow-indicators {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: 16px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.dark .work-slideshow-indicators {
    background-color: #374151;
    border-bottom: 1px solid #4b5563;
}

.work-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: none;
    background-color: #d1d5db;
    cursor: pointer;
    transition: all 0.3s ease;
}

.work-indicator:hover {
    background-color: #9ca3af;
    transform: scale(1.1);
}

.work-indicator.active {
    background-color: #3b82f6;
}

.dark .work-indicator {
    background-color: #6b7280;
}

.dark .work-indicator:hover {
    background-color: #9ca3af;
}

.dark .work-indicator.active {
    background-color: #60a5fa;
}

/* Modal-style layout */
.modal-images-stack {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.modal-image-wrapper {
    width: 100%;
    position: relative;
}

.modal-stacked-image {
    width: 100%;
    height: auto;
    display: block;
    border: none;
    border-radius: 0;
    box-shadow: none;
}

.modal-image-caption {
    padding: 12px 40px;
    background-color: #f8f9fa;
    color: #6b7280;
    font-size: 14px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.dark .modal-image-caption {
    background-color: #374151;
    color: #d1d5db;
    border-bottom: 1px solid #4b5563;
}

.modal-info {
    background-color: white;
    transition: background-color 0.3s ease;
}

.dark .modal-info {
    background-color: #111827;
}

.modal-content-text {
    color: #4b5563;
    line-height: 1.7;
    font-size: 16px;
    transition: color 0.3s ease;
}

.dark .modal-content-text {
    color: #d1d5db;
}

.modal-detail-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.modal-detail-label {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    transition: color 0.3s ease;
}

.dark .modal-detail-label {
    color: #f3f4f6;
}

.modal-detail-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    transition: color 0.3s ease;
}

.modal-detail-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

.dark .modal-detail-link {
    color: #60a5fa;
}

.dark .modal-detail-link:hover {
    color: #93c5fd;
}

.modal-tech-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.modal-tech-tag {
    background-color: #f3f4f6;
    color: #374151;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.dark .modal-tech-tag {
    background-color: #374151;
    color: #d1d5db;
    border: 1px solid #4b5563;
}

/* Content styling */
.modal-content-text h1, .modal-content-text h2, .modal-content-text h3, 
.modal-content-text h4, .modal-content-text h5, .modal-content-text h6 {
    color: #1f2937;
    font-weight: 700;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    transition: color 0.3s ease;
}

.dark .modal-content-text h1, .dark .modal-content-text h2, .dark .modal-content-text h3,
.dark .modal-content-text h4, .dark .modal-content-text h5, .dark .modal-content-text h6 {
    color: #f9fafb;
}

.modal-content-text p {
    color: #4b5563;
    line-height: 1.75;
    margin-bottom: 1em;
    transition: color 0.3s ease;
}

.dark .modal-content-text p {
    color: #d1d5db;
}

.modal-content-text a {
    color: #3b82f6;
    text-decoration: none;
    transition: color 0.3s ease;
}

.modal-content-text a:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

.dark .modal-content-text a {
    color: #60a5fa;
}

.dark .modal-content-text a:hover {
    color: #93c5fd;
}

.modal-content-text ul, .modal-content-text ol {
    color: #4b5563;
    margin-bottom: 1em;
    padding-left: 1.5em;
    transition: color 0.3s ease;
}

.dark .modal-content-text ul, .dark .modal-content-text ol {
    color: #d1d5db;
}

.modal-content-text blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 1rem;
    font-style: italic;
    color: #6b7280;
    margin: 1.5em 0;
    transition: all 0.3s ease;
}

.dark .modal-content-text blockquote {
    border-left: 4px solid #4b5563;
    color: #9ca3af;
}

.prose {
    max-width: none;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #1f2937;
    font-weight: 700;
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.7;
}

.prose ul, .prose ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose a {
    color: #2563eb;
    text-decoration: underline;
}

.prose a:hover {
    color: #1d4ed8;
}

/* Work Metrics Styles */
.work-metrics {
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
}

.dark .work-metrics {
    border-top: 1px solid #4b5563;
}

.work-metrics .metric-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.work-like-btn {
    transition: all 0.2s ease;
    border: none;
    background: none;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 0.375rem;
}

.work-like-btn:hover {
    background-color: rgba(239, 68, 68, 0.1);
}

.work-like-btn.text-red-500 svg {
    fill: currentColor;
}

.work-like-btn:not(.text-red-500) svg {
    fill: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-info {
        padding: 20px;
    }
    
    .modal-image-caption {
        padding: 12px 20px;
    }
    
    .work-metrics {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Theme management
function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.documentElement.classList.add('dark');
        updateThemeIcon(true);
    } else {
        document.documentElement.classList.remove('dark');
        updateThemeIcon(false);
    }
}

function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        updateThemeIcon(false);
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        updateThemeIcon(true);
    }
}

function updateThemeIcon(isDark) {
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');
    
    if (isDark) {
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
    } else {
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
    }
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
    initTheme();
    initWorkSlideshows();
    loadWorkMetrics();
    incrementWorkViews();
});

// Work Slideshow functionality
function initWorkSlideshows() {
    const slideshows = document.querySelectorAll('.work-slideshow-container');
    
    slideshows.forEach(slideshow => {
        const slideshowId = slideshow.dataset.slideshowId;
        const slides = slideshow.querySelectorAll('.work-slide');
        const indicators = slideshow.querySelectorAll('.work-indicator');
        
        if (slides.length <= 1) return;
        
        let currentSlide = 0;
        let slideInterval;
        
        // Show specific slide
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
            
            currentSlide = index;
        }
        
        // Next slide
        function nextSlide() {
            const nextIndex = (currentSlide + 1) % slides.length;
            showSlide(nextIndex);
        }
        
        // Start slideshow
        function startSlideshow() {
            slideInterval = setInterval(nextSlide, 3500); // 3.5 seconds
        }
        
        // Stop slideshow
        function stopSlideshow() {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
        }
        
        // Add click events to indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                showSlide(index);
                stopSlideshow();
                startSlideshow(); // Restart timer
            });
        });
        
        // Pause on hover
        slideshow.addEventListener('mouseenter', stopSlideshow);
        slideshow.addEventListener('mouseleave', startSlideshow);
        
        // Start the slideshow
        startSlideshow();
    });
}

function openLightbox(imageSrc, caption) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const lightboxCaption = document.getElementById('lightbox-caption');
    
    lightboxImage.src = imageSrc;
    lightboxCaption.textContent = caption || '';
    lightbox.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close lightbox when clicking outside the image
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Close lightbox with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    });
}

// Load work metrics (views and likes)
async function loadWorkMetrics() {
    const viewsElement = document.querySelector('.work-views-count[data-work-slug]');
    if (!viewsElement) return;
    
    const workSlug = viewsElement.getAttribute('data-work-slug');
    
    try {
        const response = await fetch(`/api/portfolio/works/${workSlug}/stats`);
        if (!response.ok) return;
        
        const data = await response.json();
        
        // Update views count
        viewsElement.textContent = data.stats.views_count || 0;
        
        // Update likes count and button state
        const likesElement = document.querySelector('.work-likes-count[data-work-slug]');
        const likeBtn = document.querySelector('.work-like-btn[data-work-slug]');
        
        if (likesElement) {
            likesElement.textContent = data.stats.likes_count || 0;
        }
        
        if (likeBtn && data.stats.is_liked !== undefined) {
            if (data.stats.is_liked) {
                likeBtn.classList.add('text-red-500');
                likeBtn.querySelector('svg').style.fill = 'currentColor';
            } else {
                likeBtn.classList.remove('text-red-500');
                likeBtn.querySelector('svg').style.fill = 'none';
            }
        }
        
    } catch (error) {
        console.error('Error loading work metrics:', error);
    }
}

// Toggle like for the work
async function toggleWorkLike(workSlug) {
    try {
        const response = await fetch(`/api/portfolio/works/${workSlug}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                user_id: 1 // For now, using a default user ID. In production, this should come from authentication
            })
        });
        
        if (!response.ok) {
            throw new Error(`Failed to toggle like: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Update likes count
        const likesElement = document.querySelector('.work-likes-count[data-work-slug]');
        const likeBtn = document.querySelector('.work-like-btn[data-work-slug]');
        
        if (likesElement) {
            likesElement.textContent = data.likes_count || 0;
        }
        
        if (likeBtn) {
            if (data.liked) {
                likeBtn.classList.add('text-red-500');
                likeBtn.querySelector('svg').style.fill = 'currentColor';
            } else {
                likeBtn.classList.remove('text-red-500');
                likeBtn.querySelector('svg').style.fill = 'none';
            }
            
            // Add animation
            likeBtn.style.transform = 'scale(1.2)';
            setTimeout(() => {
                likeBtn.style.transform = 'scale(1)';
            }, 150);
        }
        
    } catch (error) {
        console.error('Error toggling like:', error);
        alert('Erro ao curtir/descurtir. Tente novamente.');
    }
}

// Increment work views when page loads
async function incrementWorkViews() {
    const viewsElement = document.querySelector('.work-views-count[data-work-slug]');
    if (!viewsElement) return;
    
    const workSlug = viewsElement.getAttribute('data-work-slug');
    
    try {
        // Call the work detail endpoint to increment views
        await fetch(`/api/portfolio/works/${workSlug}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        // Reload metrics after incrementing views
        setTimeout(() => {
            loadWorkMetrics();
        }, 500);
        
    } catch (error) {
        console.error('Error incrementing work views:', error);
    }
}
</script>
@endpush