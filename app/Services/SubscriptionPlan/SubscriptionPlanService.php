<?php

namespace App\Services\SubscriptionPlan;

use App\Models\SubscriptionPlan;

class SubscriptionPlanService
{
    public function list(): array
    {
        try {
            $plans = SubscriptionPlan::all();

            return [
                'status' => true,
                'data' => $plans
            ];
        } catch (\Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => 400
            ];
        }
    }
}
