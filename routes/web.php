<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;

// -----------------------------
// Backoffice Blog View
// -----------------------------
Route::get('/backoffice/blog', function () {
    return view('blog'); // your Blade view for the backoffice
});

// Redirect root to backoffice blog
Route::get('/', function () {
    return redirect('/backoffice/blog');
});

// -----------------------------
// Blog API Routes
// -----------------------------
Route::prefix('api')->group(function () {

    // Fetch all posts (supports search)
    Route::get('/posts', [BlogController::class, 'fetchPosts']);

    // Create a new post
    Route::post('/posts', [BlogController::class, 'store']);

    // Update an existing post
    Route::put('/posts/{id}', [BlogController::class, 'update']);

    // Delete a post
    Route::delete('/posts/{id}', [BlogController::class, 'destroy']);

    // Update post priority (Laravel-only)
    Route::post('/posts/{id}/priority', [BlogController::class, 'updatePriority']);
});

// -----------------------------
// OAuth Login / WordPress
// -----------------------------
Route::get('auth/redirect', [AuthController::class, 'redirectToWordPress']);
Route::get('auth/callback{slash?}', [AuthController::class, 'handleWordPressCallback'])
    ->where('slash', '\/?');

// Optional home after successful OAuth
Route::get('/home', function () {
    return "Welcome to Laravel Backoffice! WordPress connected successfully.";
});
