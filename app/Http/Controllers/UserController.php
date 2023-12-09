<?php

namespace App\Http\Controllers;

use App\Events\AppBanUserAppeal;
use App\Events\BanAppUserAppeal;
use Illuminate\View\View;

use App\Models\User;
use App\Models\AppBan;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\FileController;
use App\Models\AppBanAppeal;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
    public function remove_appeal(string $username)
    {
        $user = User::where('username', $username)->get()[0];

        // $this->authorize('can_have_appeal_removed', $user);
        DB::transaction(function () use ($user) {
            $appeal = $user->app_ban->appeal_model;

            $user->app_ban->appeal = null;
            $user->app_ban->save();

            $appeal->delete();
        });
    }

    /**
     * Returns a view with the form where a user can appeal their app ban
     */
    public function show_appban_appeal_form(string $username): View
    {
        $user = User::where('username', $username)->get()[0];

        $this->authorize('can_appeal_appban', $user);

        $reason = $user->app_ban->reason;
        $appeal = $user->app_ban->appeal;

        return view(
            'pages.appban_appeal',
            [
                'username' => $username,
                'reason' => $reason,
                'alreadyAppealed' => isset($appeal)
            ]
        );
    }

    /**
     * Executes the action of submitting an appeal
     */
    public function appeal_appban(Request $request, string $username)
    {
        $request->validate([
            'reason' => 'required|string|max:512'
        ]);

        $user = User::where('username', $username)->get()[0];

        $this->authorize('can_appeal_appban', $user);

        DB::transaction(function () use ($user, $request) {
            $appban = AppBan::where('banned_user_id', $user->id)->get()[0];

            $appeal = AppBanAppeal::create([
                'reason' => $request->input('reason')
            ]);

            $appban->appeal = $appeal->id;
            $appban->save();
        });

        return redirect('/');
    }

    public function show(Request $request, string $username, string $filter = null)
    {
        $user = User::where('username', $username)->firstOrFail();

        $posts = $user->posts()->orderBy('date', 'desc')->paginate(10);

        if ($request->is("api*")) {
            $post_cards = [];
            foreach ($posts as $post) {
                $post_cards[] = view('partials.post_card', ['post' => $post, 'preview' => false])->render();
            }

            return response()->json($post_cards);
        } else {
            return view('pages.profile', [
                'user' => $user,
                'posts' => $posts
            ]);
        }
    }

    public static function reset_password(User $user, string $password)
    {
        $user->forceFill([
            'password' => Hash::make($password)
        ]);
        $user->save();

        event(new PasswordReset($user));
    }

    public function edit(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $this->authorize('update', $user);
        return view('pages.edit_profile', [
            'user' => $user
        ]);
    }

    public function update(Request $request, string $username)
    {

        // Validate the form data
        $request->validate([
            'display_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'academic_status' => 'required|in:student,teacher',
            'privacy' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the user by username**8
        $user = User::where('username', $username)->firstOrFail();
        $this->authorize('update', $user);

        // Update the user information
        $user->display_name = $request->input('display_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->academic_status = $request->input('academic_status');
        $user->is_private = $request->input('privacy') === 'private';

        // Update the password if provided
        if ($request->filled('password')) {
            try {
                $this->authorize('updatePassword', $username);
                $user->password = Hash::make($request->input('password'));
            } catch (\Throwable $th) {
                return redirect()->route('profile', ['username' => $user->username])
                    ->with('error', 'You do not have permission to change this user\'s password!');
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            FileController::upload($request->file('image'), 'profile', $user->id);
        }

        // Save the changes
        $user->save();

        return redirect()->route('profile', ['username' => $user->username])
            ->with('success', 'Profile updated successfully!');
    }

    public function unblock_user(string $username)
    {
        $user = User::where('username', '=', $username)->get()[0];

        if ($user->is_app_banned()) {
            DB::transaction(function () use ($user) {
                $app_ban = AppBan::where('banned_user_id', $user->id)->get()[0];

                $appeal = AppBanAppeal::find($app_ban->appeal);
                $app_ban->delete();

                if (isset($appeal)) {
                    $appeal->delete();
                }
            });
        }
    }

    public function checkEmailExists(string $email)
    {
        $user = User::where('email', $email)->get();
        if ($user) {
            return response()->json($user);
        } else {
            return null;
        }
    }

    public function checkUsernameExists(string $username)
    {
        $user = User::where('username', $username)->get();
        if ($user) {
            return response()->json($user);
        } else {
            return null;
        }
    }

    public function block_user(Request $request, string $username)
    {
        $rules = array('reason' => 'required|string');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return FacadesResponse::json(array('errors' => $validator->getMessageBag()->toArray()), 400); // 400 being the HTTP code for an invalid request.
        }

        $block_reason = $request->input('reason');

        $user = User::where('username', '=', $username)->get()[0];

        if (!$user->is_app_banned()) {
            AppBan::create([
                'reason' => $block_reason,
                'admin_id' => 4,
                'banned_user_id' => $user->id
            ]);
        } else {
            return response()->json(['error' => 'User is banned'], 403);
        }
    }

    public function delete_user(string $username)
    {
        $user = User::where('username', $username)->get();
        $user_id = $user[0]->id;

        if ($user === null) {
            abort(404);
        }

        DB::transaction(function () use ($user_id) {
            $this->switch_user_content_to_deleted_user($user_id);
        });
    }

    /**
     * This should be used inside a transaction
     *
     * @$user_id The id of the user we want to delete
     * */
    private function switch_user_content_to_deleted_user($user_id)
    {
        echo $user_id;

        DB::table('post')->where('author', $user_id)->update(['author' => 0]);
        DB::table('comment')->where('author', $user_id)->update(['author' => 0]);
        DB::table('reaction')->where('author', $user_id)->update(['author' => 0]);

        DB::table('group_owner')->where('user_id', $user_id)->delete();
        DB::table('group_user')->where('user_id', $user_id)->delete();

        DB::table('post_tag_not as ptn')
            ->join('post as p', 'ptn.post_id', '=', 'p.id')
            ->join('users as u', 'p.author', '=', 'u.id')
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('group_request_not as grn')
            ->join('group_request as gr', 'grn.group_request_id', '=', 'gr.id')
            ->join('users as u', 'gr.user_id', '=', 'u.id')
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('friend_request_not as frn')
            ->join('friend_request as fr', 'frn.friend_request', '=', 'fr.id')
            ->join('users as u', function ($join) {
                $join->on('u.id', '=', 'fr.user_id')
                    ->orOn('u.id', '=', 'fr.friend_id');
            })
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('friend_request')
            ->where('user_id', '=', $user_id)
            ->orWhere('friend_id', '=', $user_id)
            ->delete();

        DB::table('comment_not as cn')
            ->join('comment as c', 'cn.comment_id', '=', 'c.id')
            ->join('users as u', 'c.author', '=', 'u.id')
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('reaction_not as rn')
            ->join('reaction as r', 'rn.reaction_id', '=', 'r.id')
            ->join('users as u', 'u.id', '=', 'r.author')
            ->where('u.id', $user_id)
            ->delete();

        DB::table('friends')
            ->where('friend1', '=', $user_id)
            ->orWhere('friend2', '=', $user_id)
            ->delete();

        AppBan::where('banned_user_id', $user_id)->delete();

        DB::table('users')
            ->where('id', '=', $user_id)
            ->delete();
    }
}
