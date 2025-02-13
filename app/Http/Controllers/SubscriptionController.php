<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class SubscriptionController extends Controller
{
    // Display subscription plans
    public function showPlans()
    {
        $plans = SubscriptionPlan::all();
        return view('subscriptions.index', compact('plans'));
    }

    // Handle subscription selection
    public function subscribe($id)
{
    $user = auth()->user();
    $subscriptionPlan = SubscriptionPlan::find($id);

    if (!$subscriptionPlan) {
        return redirect()->route('subscriptions.index')->with('error', 'Subscription plan not found.');
    }

    if ($user->subscription_plan_id) {
        return redirect()->route('subscriptions.index')->with('error', 'You already have a subscription.');
    }

    // Assign subscription to user
    $user->subscription_plan_id = $subscriptionPlan->id;
    $user->save();

    return redirect()->route('dashboard')->with('success', 'You have successfully subscribed to ' . $subscriptionPlan->name . '!');
}

}
