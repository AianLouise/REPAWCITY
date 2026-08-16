<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AdoptionApplication;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FavoritesDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $user;

    protected Pet $pet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'fname' => 'A', 'lname' => 'Admin', 'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'), 'user_type' => '1',
        ]);
        $this->user = User::create([
            'fname' => 'Juan', 'lname' => 'Cruz', 'email' => 'user@gmail.com',
            'password' => Hash::make('1234'), 'user_type' => '2',
        ]);
        $this->pet = Pet::create([
            'name' => 'Cookie', 'type' => 'Dog', 'breed' => 'Labrador',
            'sex' => 'Male', 'weight' => '10-20 lbs', 'age' => '2 years',
            'date' => '2023-01-01', 'about' => 'Friendly.', 'image' => 'a.jpg',
            'is_featured' => 0, 'user_id' => $this->admin->id,
        ]);
    }

    public function test_user_can_toggle_favorite_on(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/favorites/{$this->pet->id}")
            ->assertOk()
            ->assertJsonPath('favorite', true);

        $this->assertDatabaseHas('favorites', ['user_id' => $this->user->id, 'pet_id' => $this->pet->id]);
    }

    public function test_user_can_toggle_favorite_off(): void
    {
        $this->user->favorites()->create(['pet_id' => $this->pet->id]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/favorites/{$this->pet->id}")
            ->assertOk()
            ->assertJsonPath('favorite', false);

        $this->assertDatabaseMissing('favorites', ['user_id' => $this->user->id, 'pet_id' => $this->pet->id]);
    }

    public function test_user_can_list_favorite_pets(): void
    {
        $this->user->favorites()->create(['pet_id' => $this->pet->id]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/favorites')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Cookie');
    }

    public function test_favorites_are_per_user(): void
    {
        $other = User::create([
            'fname' => 'Maria', 'lname' => 'Santos', 'email' => 'maria@test.com',
            'password' => Hash::make('1234'), 'user_type' => '2',
        ]);
        $other->favorites()->create(['pet_id' => $this->pet->id]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/favorites')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_favorites_require_auth(): void
    {
        $this->postJson("/api/favorites/{$this->pet->id}")
            ->assertStatus(401);
    }

    public function test_user_dashboard_aggregates_data(): void
    {
        $this->user->favorites()->create(['pet_id' => $this->pet->id]);

        Appointment::create([
            'appointment_type' => 'Adopt',
            'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => 'St',
            'email_address' => 'user@gmail.com',
            'status' => 'Accepted', 'message' => '', 'user_id' => $this->user->id,
        ]);

        AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'under_review', 'answers' => ['housing' => 'x'],
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonCount(1, 'upcoming_appointments')
            ->assertJsonPath('upcoming_appointments.0.status', 'Accepted')
            ->assertJsonCount(1, 'active_applications')
            ->assertJsonPath('active_applications.0.status', 'under_review')
            ->assertJsonPath('favorite_pet_ids.0', $this->pet->id)
            ->assertJsonPath('stats.appointments', 1)
            ->assertJsonPath('stats.favorites', 1);
    }

    public function test_user_dashboard_excludes_past_appointments(): void
    {
        Appointment::create([
            'appointment_type' => 'Visit',
            'appointment_date' => now()->subDays(5)->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => 'St',
            'email_address' => 'user@gmail.com',
            'status' => 'Accepted', 'message' => '', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonCount(0, 'upcoming_appointments');
    }

    public function test_user_dashboard_requires_auth(): void
    {
        $this->getJson('/api/dashboard')
            ->assertStatus(401);
    }
}
