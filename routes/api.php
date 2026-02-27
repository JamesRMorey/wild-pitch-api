<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PointOfInterestController;
use App\Http\Controllers\UploadController;

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
            Route::post('{route}/upload-image', [RouteController::class, 'uploadImage'])->name('routes.upload.image');
        });
    });

    Route::post('search', [RouteController::class, 'search'])->name('routes.search');
    Route::post('featured', [RouteController::class, 'featured'])->name('routes.featured');
    Route::get('{route}', [RouteController::class, 'find'])->name('routes.find');
});

Route::prefix('points-of-interest')->group(function() {
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('', [PointOfInterestController::class, 'index'])->name('poi.list');
        Route::post('', [PointOfInterestController::class, 'store']);

//        Route::get('bookmarked', [PointOfInterestController::class, 'bookmarked'])->name('poi.bookmark.list');
//        Route::post('{route}/bookmark', [PointOfInterestController::class, 'bookmark'])->name('poi.bookmark.create');
//        Route::delete('{route}/bookmark', [PointOfInterestController::class, 'removeBookmark'])->name('poi.bookmark.remove');

        Route::middleware('belongs.to.user:pointOfInterest')->group(function() {
            Route::put('{pointOfInterest}', [PointOfInterestController::class, 'update']);
//            Route::put('{pointOfInterest}/public', [PointOfInterestController::class, 'makePublic']);
            Route::delete('{pointOfInterest}', [PointOfInterestController::class, 'destroy']);
        });
    });

//    Route::post('search', [PointOfInterestController::class, 'search'])->name('poi.search');
//    Route::post('featured', [PointOfInterestController::class, 'featured'])->name('poi.featured');
//    Route::get('{route}', [PointOfInterestController::class, 'find'])->name('poi.find');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('upload', [UploadController::class, 'upload'])->name('upload');
    Route::delete('delete-account', [AccountController::class, 'destroy'])->name('account.delete');
});
