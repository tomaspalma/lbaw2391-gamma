<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/login');

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});

Route::controller(UserController::class)->group(function () {
    Route::post('/users/{username}', 'delete_user');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});

Route::controller(FeedController::class)->group(function () {
    Route::get('/feed', 'show_popular');
    Route::get('/feed/personal', 'show_personal');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});



// Posts
Route::controller(PostController::class)->group(function() {
    Route::get('/post', 'showCreateForm');
    Route::post('/post', 'create')->name('post.create');
    Route::get('/post/{id}', 'showPost');
    Route::get('/post/{id}/edit', 'showEditForm');
    Route::put('/post/{id}/edit', 'update')->name('post.update');
    Route::delete('/post/{id}', 'delete')->name('post.delete');
});

Route::controller(SearchController::class)->group(function () {
    Route::get("/search/{query?}", 'showSearch');
});

Route::controller(AdminController::class)->group(function () {
    Route::prefix('/admin')->name('admin')->group(function () {
        Route::get("/user", 'show_admin_user');
        Route::get("/user/create", 'show_create_user');
    });
});

Route::prefix('/api')->name('api')->group(function () {
    // Route::get('/search/users/{query}', ['searchUsers']);
    Route::controller(SearchController::class)->group(function () {
        Route::get('/search/groups/{query}', 'fullTextGroups');
        Route::get('/search/users/{query}', 'fullTextUsers');
        Route::get('/search/posts/{query}', 'fullTextPosts');
    });
});
