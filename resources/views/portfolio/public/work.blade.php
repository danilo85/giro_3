@extends('layouts.app')

@section('title', $work->meta_title ?: $work->title . ' - Portfólio - ' . config('app.name'))
@section('description', $work->meta_description ?: Str::limit($work->description, 160))

@push('meta')
<!-- Open Graph -->
<meta property="og:title" content="{{ $work->meta_title ?: $work->title }}">
<meta property="og:description" content="{{ $work->meta_description ?: Str::limit($work->description, 160) }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ route('public.portfolio.work', $work->slug) }}">
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
  "url": "{{ route('public.portfolio.work', $work->slug) }}",
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
                        <span>{{ $work->client->name }}</span>
                    </div>
                @endif
            </div>
            
            <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed transition-colors duration-300">{{ $work->description }}</p>
        </div>

        <!-- Featured Image -->
        @if($work->featured_image)
        <div class="modal-image-wrapper">
            <img src="{{ asset('storage/' . $work->featured_image) }}" 
                 alt="{{ $work->title }}" 
                 class="modal-stacked-image w-full h-auto display-block">
        </div>
        @endif

        <!-- Gallery Images - Stacked Vertically -->
        @if($work->images->count() > 0)
            <div class="modal-images-stack">
                @foreach($work->images as $image)
                    <div class="modal-image-wrapper">
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             alt="{{ $image->alt_text }}" 
                             class="modal-stacked-image w-full h-auto display-block cursor-pointer"
                             onclick="openLightbox('{{ asset('storage/' . $image->path) }}', '{{ $image->alt_text }}')">
                        @if($image->alt_text)
                            <div class="modal-image-caption">{{ $image->alt_text }}</div>
                        @endif
                    </div>
                @endforeach
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
                                <a href="{{ route('public.portfolio.work', $relatedWork->slug) }}">
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-info {
        padding: 20px;
    }
    
    .modal-image-caption {
        padding: 12px 20px;
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
});

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
</script>
@endpush