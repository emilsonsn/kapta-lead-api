<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionPlanRequest;
use App\Services\SubscriptionPlan\SubscriptionPlanService;
use Illuminate\Http\JsonResponse;
use Request;

class SubscriptionPlanController extends BaseController
{
    public function __construct(private SubscriptionPlanService $subscriptionPlanService)
    {
    }

    public function list(Request $request): JsonResponse
    {
        $result = $this->subscriptionPlanService->list();

        return $this->response($result);
    }
}
