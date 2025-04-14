<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SubscriptionPlanControllerTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser(): User
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);

        return $user;
    }

    public function test_can_list_subscription_plans()
    {        
        $this->actingAsUser();

        SubscriptionPlan::factory()->count(3)->create();

        $response = $this->getJson('/api/subscription-plan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'value', 'created_at', 'updated_at']
                ]
            ]);
    }
}
