<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioApiController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register public API routes for your application. 
| These routes are loaded by the RouteServiceProvider and will be assigned 
| to the "api-public" middleware group which excludes Sanctum stateful middleware.
|
*/

// Portfolio Public API Routes (Rotas Públicas da API do Portfólio)
Route::prefix('portfolio')->name('api.portfolio.')->group(function () {
    // Listar trabalhos públicos
    Route::get('/works', [PortfolioApiController::class, 'works'])->name('works');
    
    // Detalhes de um trabalho específico
    Route::get('/works/{work:slug}', [PortfolioApiController::class, 'work'])->name('work');
    
    // Listar categorias públicas
    Route::get('/categories', [PortfolioApiController::class, 'categories'])->name('categories');
    
    // Estatísticas do portfólio
    Route::get('/stats', [PortfolioApiController::class, 'stats'])->name('stats');
    
    // Busca no portfólio
    Route::get('/search', [PortfolioApiController::class, 'search'])->name('search');
    
    // Trabalhos relacionados
    Route::get('/works/{work:slug}/related', [PortfolioApiController::class, 'getRelatedWorks'])->name('works.related');
    
    // Imagens de um trabalho específico
    Route::get('/works/{workId}/images', [PortfolioApiController::class, 'getWorkImages'])->name('work.images');
    
    // Portfólio de um usuário específico (autor)
    Route::get('/users/{user}/works', [PortfolioApiController::class, 'userPortfolio'])->name('users.works');
    
    // Toggle like para um trabalho específico
    Route::post('/works/{work:slug}/like', [PortfolioApiController::class, 'toggleLike'])->name('works.like');
    
    // Estatísticas de um trabalho específico (visualizações e curtidas)
    Route::get('/works/{work:slug}/stats', [PortfolioApiController::class, 'getStats'])->name('works.stats');
});