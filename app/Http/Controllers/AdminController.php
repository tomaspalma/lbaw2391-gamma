<?php

namespace App\Http\Controllers;

use App\Models\AppBanAppeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function show_user_appeals(Request $request) {
        $appeals = AppBanAppeal::paginate(15);
        
        $appeal_number = count(AppBanAppeal::get());

        if($request->is("api*")) {
            $appealCards = [];

            foreach($appeals as $appeal) {
                $appealCards[] = view('partials.user_card', [ 'user'=> $appeal->appban->user, 'adminView' => true, 'appealView' => true])->render();
            }

            return response()->json($appealCards);
        } else {
            return view('pages.admin_user_appeals', [
                'appeals' => $appeals,
                'appeal_number' => $appeal_number
            ]);
        }
    }

    public function show_admin_user(Request $request)
    {
        $users = User::paginate(15);
        $appeal_number = count(AppBanAppeal::get());
        
        if($request->is("api*"))  {
            $userCards = [];

            foreach ($users as $user) {
                $userCards[] = view('partials.user_card', [ 'user'=> $user, 'adminView' => true])->render();
            }

            return response()->json($userCards);
        } else {
            return view('pages.admin', [
                'users' => $users,
                'appeal_number' => $appeal_number
            ]);
        }
    }

    /**
     *
     */
    public function show_create_user()
    {
        return view('pages.admin_create_user');
    }
}
