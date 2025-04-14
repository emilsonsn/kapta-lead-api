<?php

namespace Tests\Feature\Controllers;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChannelControllerTest extends TestCase
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

    public function test_can_list_channels()
    {
        $user = User::factory()->create();
        $this->actingAsUser();
    
        Channel::factory()->count(3)->create(['user_id' => $user->id]);
    
        $response = $this->getJson('/api/channel');
    
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'user_id', 'created_at', 'updated_at']
                ]
            ])
            ->assertJson(['status' => true]);
    }    

    public function test_store_channel()
    {
        $user = $this->actingAsUser();

        $payload = [
            'name' => 'Test Channel',
            'description' => 'Channel description',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/channel/store', $payload);

        $response->assertStatus(200)
                 ->assertJson(['status' => true]);

        $this->assertDatabaseHas('channels', [
            'name' => 'Test Channel',
            'user_id' => $user->id,
        ]);
    }

    public function test_update_channel()
    {
        $user = $this->actingAsUser();

        $channel = Channel::factory()->create(['user_id' => $user->id]);

        $payload = [
            'name' => 'Updated Channel',
            'description' => 'Updated description',
            'image' => 'updated.jpg',
            'user_id' => $user->id,
        ];

        $response = $this->patchJson("/api/channel/{$channel->id}", $payload);

        $response->assertStatus(200)
                 ->assertJson(['status' => true]);

        $this->assertDatabaseHas('channels', [
            'id' => $channel->id,
            'name' => 'Updated Channel',
        ]);
    }

    public function test_delete_channel()
    {
        $user = $this->actingAsUser();

        $channel = Channel::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/channel/{$channel->id}");

        $response->assertStatus(200)
                 ->assertJson(['status' => true]);

        $this->assertDatabaseMissing('channels', [
            'id' => $channel->id,
        ]);
    }
}
