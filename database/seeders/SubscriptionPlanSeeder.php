<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;


class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SubscriptionPlan::insert([
            ['name' => 'Basic Plan', 'course_limit' => 10],
            ['name' => 'Standard Plan', 'course_limit' => 20],
            ['name' => 'Premium Plan', 'course_limit' => 50],
        ]);
    }
}
