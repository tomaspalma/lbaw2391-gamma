<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CheckEmailExistsController extends Controller
{
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->exists();

        if ($user) {
            return response()->json(['status' => 'registered']);
        } else {
            return response()->json(['status' => 'not_registered']);
        }
    }
}

?>