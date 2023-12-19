<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'university',
        'description',
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


    public function groups(string $type): BelongsToMany
    {   
        if ($type == 'owner') return $this->belongsToMany(Group::class, 'group_owner', 'group_id', 'user_id');
        else
            return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }

    public function groupRequests()
    {
        $groupsOwner = $this->groups('owner')->get();
    
        $allRequests = [];
    
        foreach ($groupsOwner as $group) {
            $requests = $group->requests()->get();
            $allRequests = array_merge($allRequests, $requests->all());
        }
    
        return $allRequests;
    }

    public function normal_notifications()
    {
        $result = $this->comment_notifications()
            ->concat($this->reaction_notifications())
            ->concat($this->friend_request_notifications())
            ->concat($this->group_request_notifications())
            ->sortByDesc('date');

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

    public function comment_notifications()
    {
        return CommentNot::with('comment')
            ->whereHas('comment', function ($query) {
                $query->where('author', '<>', Auth::user()->id);
            })->orderBy('date', 'desc')
            ->paginate(15);
    }

    public function vote_on_post_poll(Post $post)
    {
        return DB::table("post")
            ->join('polls', 'polls.id', '=', 'post.poll_id')
            ->join('poll_options', 'poll_options.poll_id', '=', 'polls.id')
            ->join('poll_option_votes', 'poll_option_votes.poll_option_id', '=', 'poll_options.id')
            ->join('users', 'users.id', '=', 'poll_option_votes.user_id')
            ->where('users.id', $this->id)->get();
    }

    public function poll_option_on_post(Post $post) {

    }

    public function has_votes_on_option(PollOption $poll_option)
    {
        $vote = PollOptionVote::where('poll_option_id', $poll_option->id)
            ->where('user_id', $this->id)->get();

        return count($vote) > 0;
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

    public function friend_request_notifications()
    {
        return FriendRequestNot::with('friendRequest')
            ->whereHas('friendRequest', function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhere('friend_id', Auth::user()->id);
            })
            ->orderBy('date', 'desc')
            ->paginate(15);
    }

    public function group_request_notifications()
    {
        return GroupRequestNot::select('group_request_not.*')
            ->join('group_request', 'group_request.id', '=', 'group_request_not.group_request_id')
            ->join('groups', 'groups.id', '=', 'group_request.group_id')
            ->join('group_owner', 'group_owner.group_id', '=', 'groups.id')
            ->where('group_owner.user_id', Auth::user()->id)
            ->where('group_request_not.is_acceptance', '=', false)
            ->orderBy('group_request_not.date', 'desc')
            ->paginate(15);
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


    public function is_pending($group_id): bool
    {
        return DB::table('group_request')
            ->where('user_id', $this->id)
            ->where('group_id', $group_id)
            ->where('is_accepted', false)
            ->exists();
    }

    public function in_group($group): bool{
        return DB::table('group_user')
            ->where('user_id', $this->id)
            ->where('group_id', $group->id)
            ->exists() 
            || 
            DB::table('group_owner')
            ->where('user_id', $this->id)
            ->where('group_id', $group->id)
            ->exists();
    }

    public function is_owner($group): bool{
        return DB::table('group_owner')
            ->where('user_id', $this->id)
            ->where('group_id', $group)
            ->exists();
    }
    

    public function belongs_group(string $group_id): bool{
        return DB::table('group_user')
            ->where('user_id', $this->id)
            ->where('group_id', $group_id)
            ->exists();
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

    public function has_appealed_app_ban()
    {
        if (!$this->is_app_banned()) {
            return false;
        }
        return $this->app_ban->appeal !== null;
    }

    public function app_ban(): HasOne
    {
        return $this->hasOne(AppBan::class, 'banned_user_id');
    }

    public function groups_is_owner(Group $group)
    {
        $groupOwner = GroupOwner::where('group_id', $group->id)->where('user_id', $this->id)->get();

        return count($groupOwner);
    }

    public function getProfileImage()
    {
        return FileController::get('profile', $this->id);
    }

    public function sent_pending_friend_requests()
    {
        return FriendRequest::where('user_id', $this->id)
            ->where('is_accepted', null);
    }

    public function received_pending_friend_requests()
    {
        return FriendRequest::where('friend_id', $this->id)
            ->where('is_accepted', null);
    }

    public function has_friend_request_to(User $user): bool
    {
        return $this->sent_pending_friend_requests()->where('friend_id', $user->id)->exists();
    }

    public function has_friend_request_from(User $user): bool
    {
        return $this->received_pending_friend_requests()->where('user_id', $user->id)->exists();
    }
}
