<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPlanFactory extends Factory
{
    protected $model = UserPlan::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'subscription_plan_id' => SubscriptionPlan::factory(),
            'is_active' => true,
        ];
    }
}
