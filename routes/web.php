<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordController;
use App\Http\Middleware\EnsureUserExists;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\PostController;
use App\Http\Middleware\EnsurePostExists;

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
Route::redirect('/', '/feed');

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
    Route::get('/feed/personal', 'show_personal');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(EmailController::class)->group(function () {
    Route::get('/email/verify', 'show_email_verification_notice')->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', 'resend_verification')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

Route::controller(NotificationController::class)->middleware('auth')->group(function () {
    Route::get('/notifications', 'show_notifications');
});

// Posts
Route::controller(PostController::class)->group(function () {
    Route::get('/post/{id}', 'showPost')->name('post.show');
    Route::middleware('auth')->group(function () {
        Route::get('/post', 'showCreateForm')->name('post.createForm')->middleware('verified');
        Route::post('/post', 'create')->name('post.create')->middleware('verified');
        Route::get('/post/{id}/edit', 'showEditForm');
        Route::put('/post/{id}/edit', 'update')->name('post.update');
        Route::delete('/post/{id}', 'delete')->name('post.delete');
        Route::get('/post/{id}/reaction', 'get_reactions')->name('post.reactions');
        Route::post('/post/{id}/reaction', 'add_reaction')->name('post.add.reaction');
        Route::delete('/post/{id}/reaction', 'remove_reaction')->name('post.remove.reaction');
    });
});

// Comments
Route::controller(CommentController::class)->group(function () {
    Route::post('/comment', 'create')->name('comment.create');
    Route::delete('/comment/{id}', 'delete')->name('comment.delete');
});

Route::controller(GroupController::class)->group(function () {
    Route::get('/group/{id}', 'showGroupForm')->name('groupPosts');
    Route::get('/group/{id}/members', 'showGroupMembers')->name('groupMembers');
});

Route::controller(SearchController::class)->group(function () {
    Route::get("/search/{query?}", 'showSearch');
});

Route::controller(AdminController::class)->middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::get("/user", 'show_admin_user');
        Route::get("/user/create", 'show_create_user')->name('admin_create_user');
    });
});

Route::controller(PasswordController::class)->group(function () {
    Route::get('/forgot-password', 'show_forgot_password')->name('password.request');
    Route::post('/forgot-password', 'send_forgot_password_request')->name('send_reset_password_request');
    Route::get('/reset-password/{token}', 'show_reset_password')->name('password.reset');
    Route::post('/reset-password/{token}', 'reset_password')->name('password.update');
});

Route::post('/pusher/auth', [PusherController::class, 'authenticate'])->middleware('auth');

Route::prefix('/api')->group(function () {
    Route::controller(SearchController::class)->group(function () {
        Route::get('/search/groups/{query?}', 'fullTextGroups');
        Route::get('/search/users/{query?}', 'fullTextUsers');
        Route::get('/search/posts/{query?}', 'fullTextPosts');
        Route::get('/admin/search/users/{query?}', 'adminFullTextUsers')->middleware(['auth', EnsureUserIsAdmin::class]);
    });

    Route::controller(PostController::class)->group(function() {
        Route::get('/post/{id}/card/{preview}', 'show_post_card');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get("/users/username/{username}", 'checkUsernameExists');
        Route::get("/users/email/{email}", 'checkEmailExists');
    });
});
