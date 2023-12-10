<?php

namespace App\Http\Middleware;

use App\Models\AppBan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotAppBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return $next($request);
        }

        $user_is_logged_in = ($user !== null);
        if ($user_is_logged_in) {
            $appbans = AppBan::where('banned_user_id', $user->id)->get();

            if (count($appbans) > 0) {
                return redirect()->route('appban_appeal_form.show', ['username' => $user->username]);
            }
        }

        return $next($request);
    }
}
