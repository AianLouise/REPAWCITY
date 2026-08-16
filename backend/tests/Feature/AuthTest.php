<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'fname' => 'Aian Louise',
            'lname' => 'Alfaro',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'user_type' => '1',
        ]);
    }

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', [
            'fname' => 'Juan',
            'lname' => 'Dela Cruz',
            'email' => 'juan@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user' => ['id', 'fname', 'lname', 'email', 'user_type'], 'token'])
            ->assertJsonPath('user.user_type', '2');

        $this->assertDatabaseHas('users', ['email' => 'juan@test.com', 'user_type' => '2']);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        $this->postJson('/api/register', [
            'fname' => 'A', 'lname' => 'B', 'email' => 'admin@gmail.com',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_register_requires_password_confirmation(): void
    {
        $this->postJson('/api/register', [
            'fname' => 'A', 'lname' => 'B', 'email' => 'new@test.com',
            'password' => 'password123', 'password_confirmation' => 'different',
        ])->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => '1234',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['user', 'token'])
            ->assertJsonPath('user.email', 'admin@gmail.com')
            ->assertJsonPath('user.user_type', '1');
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'wrong-password',
        ])->assertStatus(401);
    }

    public function test_me_returns_authenticated_user(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.email', 'admin@gmail.com');
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/user')->assertStatus(401);
    }

    public function test_update_profile_updates_by_id(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/user/profile', [
                'fname' => 'Aian Louise',
                'lname' => 'Alfaro',
                'email' => 'admin@gmail.com',
            ])
            ->assertOk()
            ->assertJsonPath('data.lname', 'Alfaro');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'lname' => 'Alfaro']);
    }

    public function test_change_password_rejects_wrong_old_password(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/user/password', [
                'old_password' => 'nope',
                'new_password' => 'newpass123',
                'new_password_confirmation' => 'newpass123',
            ])
            ->assertStatus(422);
    }

    public function test_change_password_updates_hash(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/user/password', [
                'old_password' => '1234',
                'new_password' => 'newpass123',
                'new_password_confirmation' => 'newpass123',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Password updated successfully');

        $this->assertTrue(Hash::check('newpass123', $user->fresh()->password));
    }

    public function test_logout_revokes_token(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();
        $token = $user->createToken('spa')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
