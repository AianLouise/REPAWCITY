<?php

namespace Tests\Feature;

use App\Models\AdoptionApplication;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\Pet;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerShift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReportTest extends TestCase
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

    public function test_reports_require_admin(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/reports')
            ->assertForbidden();
    }

    public function test_reports_return_monthly_series(): void
    {
        Appointment::create([
            'appointment_type' => 'Adopt', 'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'J', 'last_name' => 'C', 'mobile_number' => '1',
            'home_address' => 's', 'email_address' => 'u@u.com',
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);
        AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'submitted', 'answers' => ['housing' => 'x'],
        ]);
        Donation::create([
            'donor_name' => 'A', 'donor_email' => 'a@a.com', 'type' => 'cash',
            'amount' => 500, 'date' => now()->toDateString(),
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/reports?months=12')
            ->assertOk()
            ->assertJsonCount(12, 'series')
            ->assertJsonPath('totals.appointments', 1)
            ->assertJsonPath('totals.applications', 1)
            ->assertJsonPath('totals.donations_cash', 500)
            ->assertJsonPath('series.11.appointments', 1);
    }

    public function test_reports_aggregate_volunteer_hours(): void
    {
        $volunteer = Volunteer::create([
            'user_id' => $this->user->id, 'status' => 'active',
            'availability' => [], 'total_hours' => 0,
        ]);
        VolunteerShift::create([
            'volunteer_id' => $volunteer->id, 'date' => now()->toDateString(),
            'time_slot' => 'Morning Session', 'hours_logged' => 4, 'activity' => 'Cleaning',
        ]);
        VolunteerShift::create([
            'volunteer_id' => $volunteer->id, 'date' => now()->toDateString(),
            'time_slot' => 'Afternoon Session', 'hours_logged' => 2, 'activity' => 'Walking',
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/reports?months=6')
            ->assertOk()
            ->assertJsonPath('totals.volunteer_hours', 6);
    }

    public function test_reports_list_top_pets_by_interest(): void
    {
        Appointment::create([
            'appointment_type' => 'Adopt', 'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'J', 'last_name' => 'C', 'mobile_number' => '1',
            'home_address' => 's', 'email_address' => 'u@u.com',
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/reports')
            ->assertOk()
            ->assertJsonPath('top_pets_by_appointments.0.name', 'Cookie')
            ->assertJsonPath('top_pets_by_appointments.0.appointments', 1);
    }

    public function test_reports_include_adoptions_count(): void
    {
        $app = AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'adopted', 'answers' => ['housing' => 'x'],
        ]);
        $app->update(['updated_at' => now()]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/reports?months=3')
            ->assertOk()
            ->assertJsonPath('totals.adoptions', 1);
    }
}
