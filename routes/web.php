<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FriendController;
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
use App\Http\Middleware\EnsureUserIsNotAppBanned;

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
    Route::middleware([EnsureUserIsNotAppBanned::class])->group(function () {
        Route::get('/users/{username}/edit', 'edit')->name('edit_profile');
        Route::put('/users/{username}/edit', 'update')->name('profile_update');
        Route::delete('/users/{username}', 'delete_user');
        Route::post('/users/{username}/block', 'block_user');
        Route::post('/users/{username}/unblock', 'unblock_user');
    });
    Route::get('/users/{username}/appban/appeal', 'show_appban_appeal_form')->name('appban_appeal_form.show');
    Route::post('/users/{username}/appban/appeal', 'appeal_appban');
});

// Friends
Route::controller(FriendController::class)->middleware(EnsureUserExists::class)->middleware('auth')->middleware(EnsureUserIsNotAppBanned::class)->group(function () {
    Route::get('/users/{username}/friends', 'show_friends')->name('friends_page');
    Route::get('/users/{username}/friends/requests', 'show_friend_requests')->name('friend_requests_page');
    Route::post('/api/users/{username}/friends/requests', 'send_friend_request')->name('send_friend_request');
    Route::put('/api/users/{username}/friends/requests', 'decline_friend_request')->name('decline_friend_request');
    Route::delete('/api/users/{username}/friends/requests', 'cancel_friend_request')->name('cancel_friend_request');
    Route::post('/api/users/{username}/friends', 'accept_friend_request')->name('accept_friend_request');
    Route::delete('/api/users/{username}/friends', 'remove_friend')->name('remove_friend');
});

Route::controller(FeedController::class)->middleware(EnsureUserIsNotAppBanned::class)->group(function () {
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

Route::controller(NotificationController::class)->middleware(['auth', EnsureUserIsNotAppBanned::class])->group(function () {
    Route::get('/notifications', 'show_notifications');
});

// Posts
Route::controller(PostController::class)->middleware(EnsureUserIsNotAppBanned::class)->group(function () {
    Route::get('/post/{id}', 'showPost')->name('post.show');
    Route::middleware(['auth', EnsureUserIsNotAppBanned::class])->group(function () {
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
Route::controller(CommentController::class)->middleware(EnsureUserIsNotAppBanned::class)->group(function () {
    Route::post('/comment', 'create')->name('comment.create');
    Route::get('/comment/{id}/edit', 'showEditForm');
    Route::put('/comment/{id}/edit', 'update')->name('comment.update');
    Route::delete('/comment/{id}', 'delete')->name('comment.delete');
    Route::get('/comment/{id}/reaction', 'get_reactions')->name('comment.reactions');
    Route::post('/comment/{id}/reaction', 'add_reaction')->name('comment.add.reaction');
    Route::delete('/comment/{id}/reaction', 'remove_reaction')->name('comment.remove.reaction');
});


Route::controller(GroupController::class)->group(function () {
    Route::get('/group/{id}', 'showGroupForm')->name('groupPosts');
    Route::get('/group/{id}/members', 'showGroupMembers')->name('groupMembers');
    Route::post('/group/{id}/enter', 'addToGroup')->name('groups.enter');
    Route::delete('/group/{id}/leave', 'removeToGroup')->name('groups.leave');
    Route::delete('/group/{id}/removeRequest', 'removeRequest')->name('groups.remove_request');
    Route::get('/groups', 'showGroupsForm');
    Route::get('/groups/requests', 'showGroupRequests');
    Route::put('/groups/{id}/approve', 'approveRequest')->name('groups.approve_request');
});

Route::controller(SearchController::class)->middleware(EnsureUserIsNotAppBanned::class)->group(function () {
    Route::get("/search/{query?}", 'showSearch');
});

Route::controller(AdminController::class)->middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::get("/user", 'show_admin_user');
        Route::get("/user/create", 'show_create_user')->name('admin_create_user');
        Route::get("/user/appeals", 'show_user_appeals')->name('admin.show_user_appeals');
    });
});

Route::controller(PasswordController::class)->middleware(EnsureUserIsNotAppBanned::class)->group(function () {
    Route::get('/forgot-password', 'show_forgot_password')->name('password.request');
    Route::post('/forgot-password', 'send_forgot_password_request')->name('send_reset_password_request');
    Route::get('/reset-password/{token}', 'show_reset_password')->name('password.reset');
    Route::post('/reset-password/{token}', 'reset_password')->name('password.update');
});

Route::post('/pusher/auth', [PusherController::class, 'authenticate'])->middleware('auth');

Route::prefix('/api')->middleware(EnsureUserIsNotAppBanned::class)->group(function () {
    Route::controller(SearchController::class)->group(function () {
        Route::get('/search/groups/{query?}', 'fullTextGroups');
        Route::get('/search/users/{query?}', 'fullTextUsers');
        Route::get('/search/posts/{query?}', 'fullTextPosts');
        Route::get('/admin/search/users/{query?}', 'adminFullTextUsers')->middleware(['auth', EnsureUserIsAdmin::class]);
        Route::get("/search/{query?}", 'showSearch');
    });

    Route::controller(FeedController::class)->group(function () {
        Route::get('/feed/popular', 'show_popular');
        Route::get('/feed/personal', 'show_personal');
    });

    Route::controller(PostController::class)->group(function () {
        Route::get('/post/{id}/card/{preview}', 'show_post_card');
        Route::get('/post/{id}/comments', 'showPost');
    });

    Route::controller(NotificationController::class)->middleware('auth')->group(function () {
        Route::get('/notifications/{filter?}', 'show_notifications');
    });

    Route::controller(GroupController::class)->group(function () {
        Route::get('/group/{group_id}/posts', 'showGroupForm')->name('api.group.show_posts');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get("/users/username/{username}", 'checkUsernameExists');
        Route::get("/users/email/{email}", 'checkEmailExists');
        Route::get("/users/{username}/posts/{filter?}", 'show');
        Route::middleware(EnsureUserIsAdmin::class)->group(function () {
            Route::delete("/users/{username}/appeal", 'remove_appeal')->name('admin.remove_appeal');
        });
    });
});
