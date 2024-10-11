<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DataFormatController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::resource('posts', PostController::class)->only(['index', 'show']);
Route::get('/search', [SearchController::class, 'index']);
Route::get('/tags', [TagController::class, 'index']);

Route::get('/likes/{post_id}', [LikeController::class, 'show']);

Route::get('/sokanAcademy', [DataFormatController::class, 'reformat']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/likes', [LikeController::class, 'store']);
    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::resource('posts', PostController::class)->only(['update', 'destroy', 'store']);

    Route::post('/publish/{post_id}', [PostController::class, 'publish']);
    Route::post('/comments/{post_id}', [CommentController::class, 'store']);   
});

Route::middleware(['Admin', 'auth:sanctum'])->group(function () {
    Route::get('/view-exports', [AdminController::class, 'showExportsList']);
    Route::get('/download-exports', [AdminController::class, 'downloadExcelFiles']);
});

