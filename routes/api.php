<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. 
| These routes are loaded by the RouteServiceProvider within a group 
| which is assigned the "api" middleware group.
|
*/

// ✅ Get all WordPress posts
Route::get('/posts', [BlogController::class, 'index']);

// ✅ Create a new WordPress post
Route::post('/posts', [BlogController::class, 'store']);

Route::middleware('auth')->get('/blogs', [BlogController::class, 'fetchPosts']);

// Optional: Default Laravel user route (kept if you use Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
