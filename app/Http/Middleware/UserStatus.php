<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = Auth::user();
        if ($user) {
            // Check user status
            switch ($user->status) {
                case 'blocked':
                    Auth::guard()->logout();
                    $request->session()->invalidate();

                    return redirect('login')->with('error', 'Oops! Your account is currently blocked.
Please reach out to our support team to resolve this issue.');
                    break;
                case 'deleted':
                    Auth::guard()->logout();
                    $request->session()->invalidate();

                    return redirect('login')->with('error', 'Oops! Your account is currently blocked.
Please reach out to our support team to resolve this issue.');
            }

            // Check blocked field (additional check)
            if ($user->blocked == 1) {
                Auth::guard()->logout();
                $request->session()->invalidate();

                return redirect('login')->with('error', 'Oops! Your account is currently blocked.
Please reach out to our support team to resolve this issue.');
            }
        }

        return $next($request);
    }
}
