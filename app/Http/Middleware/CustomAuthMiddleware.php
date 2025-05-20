<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is logged in manually
        $userId = $request->session()->get('user_id'); // Example: session key storing user ID
        if (!$userId) {
            return redirect()->route('login.show')->withErrors('Please login first.');
        }

        $user = User::find($userId);

        if (!$user) {
            // Invalid user session
            $request->session()->forget('user_id');
            return redirect()->route('login.show')->withErrors('Invalid session. Please login again.');
        }

        // Check role
        if (!in_array($user->role, $roles)) {
            return redirect()->route('login.show')->withErrors('Unauthorized access.');
        }
        
        return $next($request);
    }
}
