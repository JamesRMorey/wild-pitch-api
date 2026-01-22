<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RouteController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('routes/search', [RouteController::class, 'search'])->name('routes.search');
Route::middleware('auth:sanctum')->group(function() {
    Route::get('routes', [RouteController::class, 'index'])->name('routes.list');
    Route::post('routes', [RouteController::class, 'store']);

    Route::get('routes/bookmarked', [RouteController::class, 'bookmarked'])->name('routes.bookmark.list');
    Route::post('routes/{route}/bookmark', [RouteController::class, 'bookmark'])->name('routes.bookmark.create');
    Route::delete('routes/{route}/bookmark', [RouteController::class, 'removeBookmark'])->name('routes.bookmark.remove');

    Route::middleware('belongs.to.user:route')->group(function() {
        Route::put('routes/{route}', [RouteController::class, 'update']);
        Route::delete('routes/{route}', [RouteController::class, 'destroy']);
    });
});
