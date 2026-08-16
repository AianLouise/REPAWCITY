<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerShift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VolunteerTest extends TestCase
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

    // ---------- Volunteers ----------

    public function test_user_can_apply_to_volunteer(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/volunteers/apply', [
                'availability' => ['Weekends', 'Mornings'],
                'skills' => 'Dog handling, cleaning',
                'interests' => 'Walking dogs',
            ])
            ->assertCreated()
            ->assertJsonPath('volunteer.status', 'pending')
            ->assertJsonPath('volunteer.user_id', $this->user->id);

        $this->assertDatabaseCount('volunteers', 1);
    }

    public function test_duplicate_volunteer_application_is_rejected(): void
    {
        Volunteer::create([
            'user_id' => $this->user->id, 'status' => 'pending',
            'availability' => [], 'total_hours' => 0,
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/volunteers/apply', [])
            ->assertStatus(409);
    }

    public function test_volunteer_application_requires_auth(): void
    {
        $this->postJson('/api/volunteers/apply', [])
            ->assertStatus(401);
    }

    public function test_admin_can_approve_volunteer(): void
    {
        $volunteer = Volunteer::create([
            'user_id' => $this->user->id, 'status' => 'pending',
            'availability' => [], 'total_hours' => 0,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/volunteers/{$volunteer->id}/status", ['status' => 'active'])
            ->assertOk()
            ->assertJsonPath('volunteer.status', 'active');
    }

    public function test_regular_user_cannot_change_volunteer_status(): void
    {
        $volunteer = Volunteer::create([
            'user_id' => $this->user->id, 'status' => 'pending',
            'availability' => [], 'total_hours' => 0,
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/admin/volunteers/{$volunteer->id}/status", ['status' => 'active'])
            ->assertForbidden();
    }

    // ---------- Shifts ----------

    public function test_admin_can_assign_shift_and_volunteer_logs_hours(): void
    {
        $volunteer = Volunteer::create([
            'user_id' => $this->user->id, 'status' => 'active',
            'availability' => [], 'total_hours' => 0,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/volunteers/{$volunteer->id}/shifts", [
                'date' => now()->addDays(2)->toDateString(),
                'time_slot' => 'Morning Session',
                'activity' => 'Kennel cleaning',
            ])
            ->assertCreated();

        $shift = VolunteerShift::first();
        $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/volunteers/shifts/{$shift->id}/hours", ['hours_logged' => 4])
            ->assertOk();

        $this->assertDatabaseHas('volunteer_shifts', ['id' => $shift->id, 'hours_logged' => 4]);
        $this->assertDatabaseHas('volunteers', ['id' => $volunteer->id, 'total_hours' => 4]);
    }

    public function test_volunteer_cannot_log_hours_for_someone_elses_shift(): void
    {
        $other = User::create([
            'fname' => 'Maria', 'lname' => 'Santos', 'email' => 'maria@test.com',
            'password' => Hash::make('1234'), 'user_type' => '2',
        ]);
        $volunteer = Volunteer::create([
            'user_id' => $other->id, 'status' => 'active',
            'availability' => [], 'total_hours' => 0,
        ]);
        $shift = VolunteerShift::create([
            'volunteer_id' => $volunteer->id,
            'date' => now()->addDays(2)->toDateString(),
            'time_slot' => 'Morning Session',
            'hours_logged' => 0,
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/volunteers/shifts/{$shift->id}/hours", ['hours_logged' => 4])
            ->assertForbidden();
    }

    public function test_volunteer_can_list_own_shifts(): void
    {
        $volunteer = Volunteer::create([
            'user_id' => $this->user->id, 'status' => 'active',
            'availability' => [], 'total_hours' => 0,
        ]);
        VolunteerShift::create([
            'volunteer_id' => $volunteer->id,
            'date' => now()->addDays(2)->toDateString(),
            'time_slot' => 'Morning Session',
            'hours_logged' => 2,
            'activity' => 'Walking dogs',
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/volunteers/shifts')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.activity', 'Walking dogs');
    }
}
