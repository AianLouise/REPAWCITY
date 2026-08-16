<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\PetRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PetRecordTest extends TestCase
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

    protected function record(array $overrides = []): PetRecord
    {
        return PetRecord::create([
            'pet_id' => $this->pet->id,
            'type' => 'vaccination',
            'title' => 'Rabies shot',
            'details' => 'First rabies vaccination.',
            'record_date' => now()->toDateString(),
            'created_by' => $this->admin->id,
            ...$overrides,
        ]);
    }

    public function test_admin_can_add_care_record(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/pets/{$this->pet->id}/records", [
                'type' => 'vaccination',
                'title' => 'Rabies shot',
                'details' => 'Annual rabies booster.',
                'record_date' => now()->toDateString(),
            ])
            ->assertCreated()
            ->assertJsonPath('record.type', 'vaccination')
            ->assertJsonPath('record.created_by', 'A Admin');

        $this->assertDatabaseCount('pet_records', 1);
    }

    public function test_regular_user_cannot_add_care_record(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/admin/pets/{$this->pet->id}/records", [
                'type' => 'vaccination',
                'title' => 'Shot',
                'details' => 'Details.',
                'record_date' => now()->toDateString(),
            ])
            ->assertForbidden();
    }

    public function test_admin_can_list_all_record_types(): void
    {
        $this->record();
        $this->record(['type' => 'vet_visit', 'title' => 'Checkup']);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/pets/{$this->pet->id}/records")
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_public_index_only_exposes_non_sensitive_types(): void
    {
        $this->record(); // vaccination -> public
        $this->record(['type' => 'vet_visit', 'title' => 'Sensitive checkup']); // vet_visit -> hidden

        $this->getJson("/api/pets/{$this->pet->id}/records")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Rabies shot');
    }

    public function test_public_index_does_not_leak_creator(): void
    {
        $this->record();

        $this->getJson("/api/pets/{$this->pet->id}/records")
            ->assertOk()
            ->assertJsonMissingPath('data.0.created_by');
    }

    public function test_admin_can_delete_care_record(): void
    {
        $record = $this->record();

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/pets/{$this->pet->id}/records/{$record->id}")
            ->assertOk();

        $this->assertDatabaseCount('pet_records', 0);
    }

    public function test_pet_resource_includes_intake_info(): void
    {
        $this->pet->update([
            'intake_date' => now()->toDateString(),
            'intake_notes' => 'Found on the street.',
            'microchip' => '982000123456789',
        ]);

        $this->getJson('/api/pets/'.$this->pet->id)
            ->assertOk()
            ->assertJsonPath('data.intake_notes', 'Found on the street.')
            ->assertJsonPath('data.microchip', '982000123456789');
    }
}
