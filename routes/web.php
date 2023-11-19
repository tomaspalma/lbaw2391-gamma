<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\EnsureUserExists;
use App\Http\Middleware\EnsureUserIsAdmin;

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

// Users
Route::controller(UserController::class)->middleware(EnsureUserExists::class)->group(function () {
    Route::get('/users/{username}', 'show')->name('profile');
    Route::get('/users/{username}/edit', 'edit')->name('edit_profile');
    Route::put('/users/{username}/edit', 'update')->name('profile_update');
    Route::delete('/users/{username}', 'delete_user');
    Route::post('/users/{username}/block', 'block_user');
    Route::post('/users/{username}/unblock', 'unblock_user');
    Route::get('/api/users/{username}', 'checkUsernameExists');
});

Route::controller(FeedController::class)->group(function () {
    Route::get('/feed', 'show_popular');
    Route::get('/feed/personal', 'show_personal')->middleware('auth');
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

Route::controller(CheckEmailExistsController::class)->group(function () {
    Route::get('/checkEmailExists', 'checkEmail');
});

// Posts
Route::controller(PostController::class)->group(function () {
    Route::get('/post', 'showCreateForm');
    Route::post('/post', 'create')->name('post.create');
    Route::get('/post/{id}', 'showPost')->name('post.show');
    Route::get('/post/{id}/edit', 'showEditForm');
    Route::put('/post/{id}/edit', 'update')->name('post.update');
    Route::delete('/post/{id}', 'delete')->name('post.delete');
});

Route::controller(SearchController::class)->group(function () {
    Route::get("/search/{query?}", 'showSearch');
});

Route::controller(AdminController::class)->middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::get("/user", 'show_admin_user');
        Route::get("/user/create", 'show_create_user');
    });
});

Route::prefix('/api')->group(function () {
    Route::controller(SearchController::class)->group(function () {
        Route::get('/search/groups/{query?}', 'fullTextGroups');
        Route::get('/search/users/{query?}', 'fullTextUsers');
        Route::get('/search/posts/{query?}', 'fullTextPosts');
        Route::get('/admin/search/users/{query?}', 'adminFullTextUsers')->middleware(['auth', EnsureUserIsAdmin::class]);
    });

    Route::controller(UserController::class)->group(function () {
        Route::get("/users/username/{username}", 'checkUsernameExists');
        Route::get("/users/email/{email}", 'checkEmailExists');
    });
});
