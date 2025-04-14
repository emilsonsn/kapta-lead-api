<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => "Básico",
                'description' => "Plano Gratúito",
                'value' => 0,
                'limit' => 10,
            ],
            [
                'name' => "Premium 1",
                'description' => "Plano pago de teste",
                'value' => 10,
                'limit' => 50,
            ]
        ];

        foreach($plans as $plan){
            SubscriptionPlan::updateOrCreate($plan);
        }
    }
}
