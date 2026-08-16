<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ShelterSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'fname' => 'Aian Louise', 'lname' => 'Alfaro',
            'email' => 'admin@gmail.com', 'password' => Hash::make('1234'),
            'user_type' => '1',
        ]);

        $this->user = User::create([
            'fname' => 'Juan', 'lname' => 'Dela Cruz',
            'email' => 'juan@test.com', 'password' => Hash::make('password123'),
            'user_type' => '2',
        ]);
    }

    protected function bookingPayload(array $overrides = []): array
    {
        return array_merge([
            'appointment_type' => 'Adopt',
            'appointment_date' => now()->addDays(10)->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan',
            'middle_name' => '',
            'last_name' => 'Dela Cruz',
            'mobile_number' => '09171234567',
            'home_address' => '#135 Purok 3 Balsik',
            'email_address' => 'juan@test.com',
        ], $overrides);
    }

    public function test_store_creates_pending_appointment(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $this->bookingPayload())
            ->assertStatus(201)
            ->assertJsonPath('data.status', 'Pending')
            ->assertJsonPath('data.appointment_type', 'Adopt')
            ->assertJsonPath('data.user_id', null) // user_id intentionally not exposed
            ->assertJsonStructure(['data' => ['id', 'appointment_date', 'time_slot', 'message']]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'status' => 'Pending',
            'time_slot' => 'Morning Session',
        ]);
    }

    public function test_store_rejects_full_slot(): void
    {
        $date = now()->addDays(10)->toDateString();
        ShelterSchedule::create([
            'date' => $date,
            'is_open' => true,
            'morning_capacity' => 1,
            'afternoon_capacity' => 10,
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $this->bookingPayload(['appointment_date' => $date]))
            ->assertStatus(201);

        // Slot is at capacity -> 409
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/appointments', $this->bookingPayload([
                'appointment_date' => $date,
                'first_name' => 'Aian', 'last_name' => 'Alfaro',
            ]))
            ->assertStatus(409);

        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_store_rejects_closed_day(): void
    {
        $date = now()->addDays(10)->toDateString();
        ShelterSchedule::create([
            'date' => $date,
            'is_open' => false,
            'reason' => 'Shelter maintenance',
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $this->bookingPayload(['appointment_date' => $date]))
            ->assertStatus(409);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_store_requires_authentication(): void
    {
        $this->postJson('/api/appointments', $this->bookingPayload())
            ->assertStatus(401);
    }

    public function test_store_rejects_past_date(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $this->bookingPayload([
                'appointment_date' => now()->subDays(5)->toDateString(),
            ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('appointment_date');
    }

    public function test_store_rejects_invalid_type_and_slot(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $this->bookingPayload([
                'appointment_type' => 'Invalid',
                'time_slot' => 'Evening Session',
            ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['appointment_type', 'time_slot']);
    }

    public function test_store_assigns_user_id_from_token(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $this->bookingPayload())
            ->assertStatus(201);

        $this->assertDatabaseHas('appointments', ['user_id' => $this->user->id]);
    }

    public function test_my_appointments_lists_only_own(): void
    {
        Appointment::create([...$this->bookingPayload(), 'status' => 'Pending',
            'message' => 'pending', 'user_id' => $this->user->id]);
        Appointment::create([...$this->bookingPayload([
            'appointment_date' => now()->addDays(11)->toDateString(),
            'time_slot' => 'Afternoon Session',
        ]), 'status' => 'Pending', 'message' => 'pending', 'user_id' => $this->admin->id]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/appointments/my')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_message_returns_own_appointment_message(): void
    {
        $appt = Appointment::create([...$this->bookingPayload(), 'status' => 'Accepted',
            'message' => 'Your appointment is confirmed.', 'user_id' => $this->user->id]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/appointments/{$appt->id}/message")
            ->assertOk()
            ->assertJsonPath('message', 'Your appointment is confirmed.')
            ->assertJsonPath('status', 'Accepted');
    }

    public function test_message_forbidden_for_other_user(): void
    {
        $appt = Appointment::create([...$this->bookingPayload(), 'status' => 'Pending',
            'message' => 'pending', 'user_id' => $this->admin->id]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/appointments/{$appt->id}/message")
            ->assertForbidden();
    }
}
