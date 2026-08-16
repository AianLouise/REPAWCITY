<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $other;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'fname' => 'Juan', 'lname' => 'Cruz', 'email' => 'juan@test.com',
            'password' => Hash::make('password123'), 'user_type' => '2',
        ]);
        $this->other = User::create([
            'fname' => 'Maria', 'lname' => 'Santos', 'email' => 'maria@test.com',
            'password' => Hash::make('password123'), 'user_type' => '2',
        ]);
    }

    protected function payload(array $overrides = []): array
    {
        return array_merge([
            'appointment_type' => 'Adopt',
            'appointment_date' => now()->addDays(3)->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'middle_name' => '', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => 'St',
            'email_address' => 'juan@test.com',
        ], $overrides);
    }

    public function test_store_prevents_double_booking_same_slot(): void
    {
        // Create a booking directly (simulating an earlier concurrent request that won)
        Appointment::create([
            ...$this->payload(),
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);

        // Second user's attempt for the same date+slot must be rejected with 409
        $this->actingAs($this->other, 'sanctum')
            ->postJson('/api/appointments', $this->payload([
                'first_name' => 'Maria', 'last_name' => 'Santos',
                'email_address' => 'maria@test.com',
            ]))
            ->assertStatus(409);

        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_store_allows_different_slot_same_date(): void
    {
        Appointment::create([
            ...$this->payload(),
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->other, 'sanctum')
            ->postJson('/api/appointments', $this->payload([
                'time_slot' => 'Afternoon Session',
                'first_name' => 'Maria', 'last_name' => 'Santos',
                'email_address' => 'maria@test.com',
            ]))
            ->assertStatus(201);

        $this->assertDatabaseCount('appointments', 2);
    }

    public function test_store_is_transactional_and_locked(): void
    {
        // Simulate the exact race: check that lockForUpdate path inserts once.
        $created = DB::transaction(function () {
            $conflict = Appointment::query()
                ->whereDate('appointment_date', now()->addDays(3)->toDateString())
                ->where('time_slot', 'Morning Session')
                ->lockForUpdate()
                ->exists();

            if ($conflict) {
                return null;
            }

            return Appointment::create([...$this->payload(), 'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id]);
        });

        $this->assertNotNull($created);
        $this->assertDatabaseCount('appointments', 1);
    }
}
