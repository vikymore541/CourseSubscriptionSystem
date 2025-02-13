<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasSubscription
{
    public function handle(Request $request, Closure $next)
{
    // Allow unauthenticated users to access login and register pages
    if (!auth()->check()) {
        return $next($request);
    }

    $user = auth()->user();

    // Allow access to subscription routes and dashboard without subscription
    if (!$user->subscription_plan_id && !in_array($request->route()->getName(), ['dashboard', 'subscriptions.index', 'subscriptions.subscribe'])) {
        return redirect()->route('subscriptions.index')->with('error', 'You must subscribe before accessing courses.');
    }

    return $next($request);
}

    
    
}
