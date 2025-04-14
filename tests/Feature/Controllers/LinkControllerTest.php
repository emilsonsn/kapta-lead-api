<?php

namespace Tests\Feature\Controllers;

use App\Models\Channel;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Constants\ResponseMessage;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser($user): User
    {
        $token = JWTAuth::fromUser($user);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);

        return $user;
    }

    public function test_can_list_links()
    {
        $user = User::factory()->create();
        $this->actingAsUser($user);

        $channel = Channel::factory()->create(['user_id' => $user->id]);
        Link::factory()->count(3)->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id
        ]);

        $response = $this->getJson('/api/link');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'channel_id', 'user_id', 'description', 'destination_url', 'hash', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_can_store_link()
    {
        $user = User::factory()->create();
        $this->actingAsUser($user);
    
        $plan = \App\Models\SubscriptionPlan::factory()->create(['limit' => 5]);
        \App\Models\UserPlan::factory()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'is_active' => true,
        ]);
    
        $channel = Channel::factory()->create(['user_id' => $user->id]);
    
        $payload = [
            'channel_id' => $channel->id,
            'description' => 'Test link',
            'destination_url' => 'https://example.com'
        ];
    
        $response = $this->postJson('/api/link', $payload);
    
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => ResponseMessage::LINK_CREATED]);
    }
    
    public function test_cannot_store_link_when_limit_is_reached()
    {
        $user = User::factory()->create();
        $this->actingAsUser($user);
    
        $plan = \App\Models\SubscriptionPlan::factory()->create(['limit' => 1]);
        \App\Models\UserPlan::factory()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'is_active' => true,
        ]);
    
        $channel = Channel::factory()->create(['user_id' => $user->id]);
    
        Link::factory()->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id
        ]);
    
        $payload = [
            'channel_id' => $channel->id,
            'description' => 'Another test link',
            'destination_url' => 'https://example.com'
        ];
    
        $response = $this->postJson('/api/link', $payload);
    
        $response->assertStatus(400)
        ->assertJsonFragment(['error' => ResponseMessage::LINK_LIMIT_REACHED]);
    
    }    

    public function test_can_update_link()
    {
        $user = User::factory()->create();
        $this->actingAsUser($user);

        $channel = Channel::factory()->create(['user_id' => $user->id]);
        $link = Link::factory()->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id
        ]);

        $payload = [
            'channel_id' => $channel->id,
            'description' => 'Updated link',
            'destination_url' => 'https://updated.com'
        ];

        $response = $this->putJson("/api/link/{$link->id}", $payload);
        
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => ResponseMessage::LINK_UPDATED]);
    }

    public function test_can_delete_link()
    {
        $user = User::factory()->create();
        $this->actingAsUser($user);
        $channel = Channel::factory()->create(['user_id' => $user->id]);
        $link = Link::factory()->create([
            'user_id' => $user->id,
            'channel_id' => $channel->id
        ]);

        $response = $this->deleteJson("/api/link/{$link->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => ResponseMessage::LINK_DELETED]);
    }
}
