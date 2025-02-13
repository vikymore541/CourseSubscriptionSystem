<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    //
    public function users()
{
    return $this->hasMany(User::class, 'subscription_plan_id');
}
}
