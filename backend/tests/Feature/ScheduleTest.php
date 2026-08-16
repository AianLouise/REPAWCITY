<?php

namespace Tests\Feature;

use App\Models\ShelterSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $user;

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
    }

    public function test_public_schedules_returns_next_60_days_open_by_default(): void
    {
        $this->getJson('/api/schedules')
            ->assertOk()
            ->assertJsonCount(61, 'data')
            ->assertJsonPath('data.0.is_open', true)
            ->assertJsonPath('data.0.morning_capacity', 10);
    }

    public function test_public_schedules_reflects_closed_day(): void
    {
        $date = now()->addDays(3)->toDateString();
        ShelterSchedule::create([
            'date' => $date, 'is_open' => false, 'reason' => 'Shelter event',
        ]);

        $this->getJson('/api/schedules')
            ->assertOk()
            ->assertJsonPath('data.3.is_open', false)
            ->assertJsonPath('data.3.reason', 'Shelter event');
    }

    public function test_admin_can_update_schedule(): void
    {
        $date = now()->addDays(5)->toDateString();

        $this->actingAs($this->admin, 'sanctum')
            ->putJson('/api/admin/schedules', [
                'date' => $date,
                'is_open' => false,
                'morning_capacity' => 4,
                'afternoon_capacity' => 6,
                'reason' => 'Adoption event',
            ])
            ->assertOk()
            ->assertJsonPath('schedule.date', $date)
            ->assertJsonPath('schedule.is_open', false);

        $this->assertDatabaseHas('shelter_schedules', ['is_open' => false]);
        $this->assertDatabaseCount('shelter_schedules', 1);
    }

    public function test_regular_user_cannot_update_schedule(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/admin/schedules', [
                'date' => now()->addDays(5)->toDateString(),
                'is_open' => false,
                'morning_capacity' => 4,
                'afternoon_capacity' => 6,
            ])
            ->assertForbidden();
    }

    public function test_slots_reports_closed_day(): void
    {
        $date = now()->addDays(2)->toDateString();
        ShelterSchedule::create(['date' => $date, 'is_open' => false]);

        $this->getJson('/api/appointments/slots?date='.$date)
            ->assertOk()
            ->assertJsonPath('is_open', false)
            ->assertJsonPath('fully_booked', true);
    }

    public function test_slots_reports_capacity(): void
    {
        $date = now()->addDays(2)->toDateString();
        ShelterSchedule::create([
            'date' => $date, 'is_open' => true,
            'morning_capacity' => 1, 'afternoon_capacity' => 2,
        ]);

        $this->getJson('/api/appointments/slots?date='.$date)
            ->assertOk()
            ->assertJsonPath('morning_capacity', 1)
            ->assertJsonPath('afternoon_capacity', 2)
            ->assertJsonPath('morning_full', false)
            ->assertJsonPath('fully_booked', false);
    }

    public function test_admin_schedule_index_returns_range(): void
    {
        $date = now()->addDays(1)->toDateString();
        ShelterSchedule::create(['date' => $date, 'is_open' => false]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/schedules?from='.now()->toDateString().'&to='.now()->addDays(7)->toDateString())
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.date', $date);
    }
}
