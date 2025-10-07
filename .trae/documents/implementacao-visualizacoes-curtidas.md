# Guia de Implementação - Sistema de Visualizações e Curtidas

## 1. Migrações do Banco de Dados

### 1.1 Adicionar coluna views_count
```bash
php artisan make:migration add_views_count_to_portfolio_works_table
```

**Conteúdo da migração:**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_works', function (Blueprint $table) {
            $table->integer('views_count')->default(0)->after('is_featured');
            $table->index('views_count');
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_works', function (Blueprint $table) {
            $table->dropIndex(['views_count']);
            $table->dropColumn('views_count');
        });
    }
};
```

### 1.2 Criar tabela de curtidas
```bash
php artisan make:migration create_portfolio_work_likes_table
```

**Conteúdo da migração:**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_work_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('portfolio_work_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'portfolio_work_id'], 'unique_user_work_like');
            $table->index('user_id');
            $table->index('portfolio_work_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_work_likes');
    }
};
```

## 2. Modelos Eloquent

### 2.1 Atualizar PortfolioWork Model
```php
// Adicionar ao $fillable
protected $fillable = [
    // ... campos existentes
    'views_count',
];

// Adicionar relacionamento
public function likes()
{
    return $this->hasMany(PortfolioWorkLike::class);
}

// Método para incrementar visualizações
public function incrementViews()
{
    $this->increment('views_count');
}

// Método para verificar se usuário curtiu
public function isLikedBy($user)
{
    if (!$user) return false;
    return $this->likes()->where('user_id', $user->id)->exists();
}

// Accessor para contagem de curtidas
public function getLikesCountAttribute()
{
    return $this->likes()->count();
}
```

### 2.2 Criar PortfolioWorkLike Model
```bash
php artisan make:model PortfolioWorkLike
```

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioWorkLike extends Model
{
    protected $fillable = [
        'user_id',
        'portfolio_work_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function portfolioWork(): BelongsTo
    {
        return $this->belongsTo(PortfolioWork::class);
    }
}
```

## 3. Controllers e Rotas

### 3.1 Atualizar PortfolioApiController
```php
// Método para toggle de curtida
public function toggleLike(PortfolioWork $work)
{
    $user = auth()->user();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuário não autenticado'
        ], 401);
    }

    $like = $work->likes()->where('user_id', $user->id)->first();
    
    if ($like) {
        $like->delete();
        $liked = false;
    } else {
        $work->likes()->create(['user_id' => $user->id]);
        $liked = true;
    }

    return response()->json([
        'success' => true,
        'liked' => $liked,
        'likes_count' => $work->likes()->count()
    ]);
}

// Método para obter estatísticas
public function getStats(PortfolioWork $work)
{
    $user = auth()->user();
    
    return response()->json([
        'views_count' => $work->views_count,
        'likes_count' => $work->likes()->count(),
        'user_liked' => $work->isLikedBy($user)
    ]);
}

// Atualizar workDetail para incrementar views
public function workDetail(PortfolioWork $work)
{
    if ($work->status !== 'published') {
        abort(404);
    }

    $work->load([
        'category',
        'client',
        'images' => function($query) {
            $query->ordered();
        },
        'authors'
    ]);

    // Incrementar visualizações
    $work->incrementViews();

    $relatedWorks = $this->getRelatedWorks($work, 3);

    return view('portfolio.public.work', compact('work', 'relatedWorks'));
}
```

### 3.2 Adicionar rotas em api.php
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/portfolio/works/{work}/like', [PortfolioApiController::class, 'toggleLike']);
});

Route::get('/portfolio/works/{work}/stats', [PortfolioApiController::class, 'getStats']);
```

## 4. Frontend Implementation

### 4.1 JavaScript para curtidas
```javascript
// Função para toggle de curtida
async function toggleLike(workId) {
    try {
        const response = await fetch(`/api/portfolio/works/${workId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.success) {
            updateLikeButton(workId, data.liked, data.likes_count);
        }
    } catch (error) {
        console.error('Erro ao curtir:', error);
    }
}

// Atualizar botão de curtida
function updateLikeButton(workId, liked, count) {
    const button = document.querySelector(`[data-work-id="${workId}"]`);
    const icon = button.querySelector('.heart-icon');
    const counter = button.querySelector('.likes-count');
    
    if (liked) {
        icon.classList.add('text-red-500', 'fill-current');
        icon.classList.remove('text-gray-400');
    } else {
        icon.classList.remove('text-red-500', 'fill-current');
        icon.classList.add('text-gray-400');
    }
    
    counter.textContent = count;
    
    // Animação de pulse
    button.classList.add('animate-pulse');
    setTimeout(() => button.classList.remove('animate-pulse'), 300);
}
```

### 4.2 Componente de métricas
```html
<!-- Componente reutilizável para exibir métricas -->
<div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
    <!-- Visualizações -->
    <div class="flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        <span>{{ number_format($work->views_count) }}</span>
    </div>
    
    <!-- Curtidas -->
    <div class="flex items-center">
        @auth
            <button onclick="toggleLike({{ $work->id }})" 
                    data-work-id="{{ $work->id }}"
                    class="flex items-center hover:text-red-500 transition-colors duration-200">
        @endauth
        
        <svg class="w-4 h-4 mr-1 heart-icon {{ $work->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
             fill="{{ $work->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" 
             stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
        <span class="likes-count">{{ $work->likes_count }}</span>
        
        @auth
            </button>
        @endauth
    </div>
</div>
```

## 5. Locais de Implementação

### 5.1 welcome.blade.php - Modal do trabalho
- Adicionar métricas na parte inferior do modal
- Incluir JavaScript para curtidas

### 5.2 portfolio/works/index.blade.php - Cards
- Substituir linha 256 atual por componente de métricas
- Adicionar funcionalidade de curtida nos cards

### 5.3 portfolio/public/work.blade.php - Página pública
- Exibir métricas em destaque
- Implementar botão de curtida interativo

## 6. Comandos para Execução

```bash
# Executar migrações
php artisan migrate

# Limpar cache se necessário
php artisan cache:clear
php artisan view:clear

# Testar funcionalidades
php artisan tinker
# PortfolioWork::first()->incrementViews()
```