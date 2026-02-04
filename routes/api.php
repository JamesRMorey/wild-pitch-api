<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RouteController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::prefix('routes')->group(function() {
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('', [RouteController::class, 'index'])->name('routes.list');
        Route::post('', [RouteController::class, 'store']);

        Route::get('bookmarked', [RouteController::class, 'bookmarked'])->name('routes.bookmark.list');
        Route::post('{route}/bookmark', [RouteController::class, 'bookmark'])->name('routes.bookmark.create');
        Route::delete('{route}/bookmark', [RouteController::class, 'removeBookmark'])->name('routes.bookmark.remove');

        Route::middleware('belongs.to.user:route')->group(function() {
            Route::put('{route}', [RouteController::class, 'update']);
            Route::put('{route}/public', [RouteController::class, 'makePublic']);
            Route::delete('{route}', [RouteController::class, 'destroy']);
        });
    });

    Route::post('search', [RouteController::class, 'search'])->name('routes.search');
    Route::post('featured', [RouteController::class, 'featured'])->name('routes.featured');
    Route::get('{route}', [RouteController::class, 'find'])->name('routes.find');
});
