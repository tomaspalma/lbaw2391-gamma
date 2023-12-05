<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use PasswordsCanResetPassword;

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'image',
        'academic_status',
        'display_name',
        'is_private',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }

    public function normal_notifications()
    {
        $result = $this->comment_notification()->concat($this->reaction_notifications())->sortByDesc('date');

        return $result;
    }


    public function reaction_notifications()
    {
        return ReactionNot::with('reaction')
            ->whereHas('reaction', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('author', '<>', Auth::user()->id)
                        ->where(function ($sq) {
                            $sq->orWhereHas('post', function ($postQuery) {
                                $postQuery->where('author', Auth::user()->id);
                            })
                                ->orWhereHas('comment', function ($commentQuery) {
                                    $commentQuery->where('author', Auth::user()->id);
                                });
                        });
                });
            })->orderBy('date', 'desc')
            ->paginate(15);
    }

    public function comment_notification()
    {
        return CommentNot::with('comment')
            ->whereHas('comment', function ($query) {
                $query->where('author', Auth::user()->id);
            })->orderBy('date', 'desc')
            ->paginate(15)
    }

    public function comment_reaction(Comment $comment)
    {
        $reactions = Reaction::where('comment_id', $comment->id)->where('author', $this->id)->get();

        $comment_reactions = [];

        foreach ($reactions as $reaction) {
            $comment_reactions[$reaction->type->value] = [
                $reaction->type->getViewIcon(),
                $reaction->type->getViewColor(),
            ];
        }

        return $comment_reactions;
    }

    public function post_reaction(Post $post)
    {
        $reactions = Reaction::where('post_id', $post->id)->where('author', $this->id)->get();

        $user_post_reactions = [];

        foreach ($reactions as $reaction) {
            $user_post_reactions[$reaction->type->value] = [
                $reaction->type->getViewIcon(),
                $reaction->type->getViewColor(),
            ];
        }

        return $user_post_reactions;
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend1', 'friend2')
            ->union($this->belongsToMany(User::class, 'friends', 'friend2', 'friend1'))
            ->where('id', '<>', $this->id);
    }

    public function has_verified_email(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function is_friend(User $user): bool
    {
        return $this->friends->contains($user);
    }

    public function friend($user_id): bool
    {
        return $this->friends()->get()->contains($user_id);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, "author");
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, "author");
    }

    public function is_admin(): bool
    {
        return $this->role === 1;
    }

    public function is_app_banned(): bool
    {
        return $this->app_ban !== null;
    }

    public function app_ban(): HasOne
    {
        return $this->hasOne(AppBan::class, 'banned_user_id');
    }

    public function getProfileImage()
    {
        return FileController::get('profile', $this->id);
    }
}
