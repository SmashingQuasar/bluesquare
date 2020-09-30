<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/post/new', [PostController::class, 'new'])->name('post.new');
Route::post('/post/create', [PostController::class, 'create'])->name('post.create');
Route::get('/post/read', [PostController::class, 'read'])->name('post.read');
Route::get('/post/all', [PostController::class, 'all'])->name('post.all');