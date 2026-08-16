<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PetStatusLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Pet $pet;

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
            'sex' => 'Male', 'weight' => '10-20 lbs', 'age' => '6 months to 5 years',
            'date' => '2023-01-01', 'about' => 'Friendly.', 'image' => 'a.jpg',
            'is_featured' => 0, 'user_id' => $this->admin->id,
        ]);
    }

    public function test_pets_list_excludes_adopted_and_deceased_by_default(): void
    {
        $this->pet->update(['status' => Pet::STATUS_ADOPTED]);
        Pet::create([
            'name' => 'Buddy', 'type' => 'Dog', 'breed' => 'Aspin',
            'sex' => 'Male', 'weight' => '5-10 lbs', 'age' => '1 year',
            'date' => '2023-01-01', 'about' => 'Happy.', 'image' => 'b.jpg',
            'is_featured' => 0, 'user_id' => $this->admin->id, 'status' => Pet::STATUS_DECEASED,
        ]);

        $this->getJson('/api/pets')
            ->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['name' => 'Cookie'])
            ->assertJsonMissing(['name' => 'Buddy']);
    }

    public function test_pets_list_can_include_unavailable_with_flag(): void
    {
        $this->pet->update(['status' => Pet::STATUS_ADOPTED]);

        $this->getJson('/api/pets?include_unavailable=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Cookie');
    }

    public function test_pets_list_filters_by_status(): void
    {
        $this->pet->update(['status' => Pet::STATUS_ON_HOLD]);

        $this->getJson('/api/pets?status=on_hold')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'on_hold');
    }

    public function test_pet_resource_includes_status(): void
    {
        $this->getJson('/api/pets/'.$this->pet->id)
            ->assertOk()
            ->assertJsonPath('data.status', 'available');
    }

    public function test_admin_can_set_pet_status(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/pets/'.$this->pet->id.'/status', ['status' => 'on_hold'])
            ->assertOk()
            ->assertJsonPath('pet.status', 'on_hold');

        $this->assertDatabaseHas('pets', ['id' => $this->pet->id, 'status' => 'on_hold']);
    }

    public function test_regular_user_cannot_set_pet_status(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/pets/'.$this->pet->id.'/status', ['status' => 'adopted'])
            ->assertForbidden();
    }

    public function test_set_status_rejects_invalid_value(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/pets/'.$this->pet->id.'/status', ['status' => 'unknown'])
            ->assertUnprocessable();
    }

    public function test_appointment_can_be_linked_to_a_pet(): void
    {
        $payload = [
            'appointment_type' => 'Adopt',
            'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => '123 St',
            'email_address' => 'user@gmail.com',
        ];

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $payload)
            ->assertCreated()
            ->assertJsonPath('data.pet.id', $this->pet->id);
    }

    public function test_appointment_for_adopted_pet_is_rejected(): void
    {
        $this->pet->update(['status' => Pet::STATUS_ADOPTED]);

        $payload = [
            'appointment_type' => 'Adopt',
            'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => '123 St',
            'email_address' => 'user@gmail.com',
        ];

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('pet_id');

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_appointment_for_on_hold_pet_is_allowed(): void
    {
        $this->pet->update(['status' => Pet::STATUS_ON_HOLD]);

        $payload = [
            'appointment_type' => 'Adopt',
            'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'time_slot' => 'Afternoon Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => '123 St',
            'email_address' => 'user@gmail.com',
        ];

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $payload)
            ->assertCreated();
    }

    public function test_appointment_without_pet_is_allowed(): void
    {
        $payload = [
            'appointment_type' => 'Visit',
            'appointment_date' => now()->addDay()->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => '123 St',
            'email_address' => 'user@gmail.com',
        ];

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/appointments', $payload)
            ->assertCreated()
            ->assertJsonPath('data.pet', null);
    }

    public function test_my_appointments_include_linked_pet(): void
    {
        $appointment = Appointment::create([
            'appointment_type' => 'Adopt',
            'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => '123 St',
            'email_address' => 'user@gmail.com',
            'status' => 'Pending',
            'message' => '',
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/appointments/my')
            ->assertOk()
            ->assertJsonPath('data.0.id', $appointment->id)
            ->assertJsonPath('data.0.pet.id', $this->pet->id);
    }
}
