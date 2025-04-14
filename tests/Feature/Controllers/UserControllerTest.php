<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;



class UserControllerTest extends TestCase
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
    
    public function test_me_endpoint_returns_authenticated_user()
    {
        $user = $this->actingAsUser();

        $response = $this->getJson('/api/user/me');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'data' => [
                         'id' => $user->id,
                         'email' => $user->email,
                     ]
                 ]);
    }

    public function test_store_user()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'cpf_cnpj' => '12345678900',
            'role' => 'User',
            'password' => 'password',
        ];

        $this->actingAsUser();

        $response = $this->postJson('/api/user/store', $payload);

        $response->assertStatus(200)
                 ->assertJson(['status' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_update_user()
    {
        Storage::fake('public');

        $user = $this->actingAsUser();

        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '987654321',
            'cpf_cnpj' => '98765432100',
            'role' => 'User',
            'password' => 'newpassword',
        ];

        $response = $this->patchJson("/api/user/{$user->id}", $payload);

        $response->assertStatus(200)
                 ->assertJson(['status' => true]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updated@example.com',
            'name' => 'Updated Name',
        ]);
    }

    public function test_delete_user_soft_deletes_the_user()
    {
        $user = $this->actingAsUser();

        $response = $this->deleteJson("/api/user/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson(['status' => true]);

        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }
}