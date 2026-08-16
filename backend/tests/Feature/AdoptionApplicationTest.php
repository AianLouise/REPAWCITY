<?php

namespace Tests\Feature;

use App\Models\AdoptionApplication;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdoptionApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $user;

    protected User $other;

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
        $this->other = User::create([
            'fname' => 'Maria', 'lname' => 'Santos', 'email' => 'maria@gmail.com',
            'password' => Hash::make('1234'), 'user_type' => '2',
        ]);
        $this->pet = Pet::create([
            'name' => 'Cookie', 'type' => 'Dog', 'breed' => 'Labrador',
            'sex' => 'Male', 'weight' => '10-20 lbs', 'age' => '2 years',
            'date' => '2023-01-01', 'about' => 'Friendly.', 'image' => 'a.jpg',
            'is_featured' => 0, 'user_id' => $this->admin->id,
        ]);
    }

    protected function answers(array $overrides = []): array
    {
        return array_merge([
            'housing' => 'House with a yard.',
            'other_pets' => 'One friendly cat.',
            'experience' => 'Grew up with dogs.',
            'why_this_pet' => 'Great temperament.',
        ], $overrides);
    }

    public function test_user_can_submit_application(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/adoption-applications', [
                'pet_id' => $this->pet->id,
                'answers' => $this->answers(),
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'submitted')
            ->assertJsonPath('data.pet.id', $this->pet->id);

        $this->assertDatabaseCount('adoption_applications', 1);
    }

    public function test_duplicate_active_application_is_rejected(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/adoption-applications', [
                'pet_id' => $this->pet->id,
                'answers' => $this->answers(),
            ])
            ->assertCreated();

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/adoption-applications', [
                'pet_id' => $this->pet->id,
                'answers' => $this->answers(),
            ])
            ->assertStatus(409);
    }

    public function test_different_users_can_apply_for_same_pet(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/adoption-applications', [
                'pet_id' => $this->pet->id,
                'answers' => $this->answers(),
            ])
            ->assertCreated();

        $this->actingAs($this->other, 'sanctum')
            ->postJson('/api/adoption-applications', [
                'pet_id' => $this->pet->id,
                'answers' => $this->answers(),
            ])
            ->assertCreated();

        $this->assertDatabaseCount('adoption_applications', 2);
    }

    public function test_application_rejected_for_unavailable_pet(): void
    {
        $this->pet->update(['status' => Pet::STATUS_ADOPTED]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/adoption-applications', [
                'pet_id' => $this->pet->id,
                'answers' => $this->answers(),
            ])
            ->assertStatus(422);
    }

    public function test_application_requires_all_answers(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/adoption-applications', [
                'pet_id' => $this->pet->id,
                'answers' => ['housing' => 'Only this one filled.'],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['answers.other_pets', 'answers.experience', 'answers.why_this_pet']);
    }

    public function test_user_can_list_own_applications(): void
    {
        AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'submitted', 'answers' => $this->answers(),
        ]);
        AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->other->id,
            'status' => 'submitted', 'answers' => $this->answers(),
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/adoption-applications/my')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_cancel_own_application(): void
    {
        $app = AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'submitted', 'answers' => $this->answers(),
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/adoption-applications/{$app->id}/cancel")
            ->assertOk()
            ->assertJsonPath('application.status', 'rejected');
    }

    public function test_user_cannot_cancel_others_application(): void
    {
        $app = AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->other->id,
            'status' => 'submitted', 'answers' => $this->answers(),
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/adoption-applications/{$app->id}/cancel")
            ->assertForbidden();
    }

    public function test_admin_can_list_all_applications(): void
    {
        AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'submitted', 'answers' => $this->answers(),
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/adoption-applications')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.pet.id', $this->pet->id);
    }

    public function test_regular_user_cannot_list_applications(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/adoption-applications')
            ->assertForbidden();
    }

    public function test_admin_can_advance_application_to_under_review(): void
    {
        Mail::fake();
        $app = AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'submitted', 'answers' => $this->answers(),
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/adoption-applications/{$app->id}/status", [
                'status' => 'under_review',
                'notes' => 'Schedule a home visit.',
            ])
            ->assertOk()
            ->assertJsonPath('application.status', 'under_review')
            ->assertJsonPath('application.notes', 'Schedule a home visit.');
    }

    public function test_admin_can_approve_then_adopt_and_pet_updates(): void
    {
        Mail::fake();
        $app = AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'under_review', 'answers' => $this->answers(),
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/adoption-applications/{$app->id}/status", ['status' => 'approved'])
            ->assertOk();

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/adoption-applications/{$app->id}/status", ['status' => 'adopted'])
            ->assertOk()
            ->assertJsonPath('application.status', 'adopted');

        $this->assertDatabaseHas('pets', ['id' => $this->pet->id, 'status' => 'adopted']);
    }

    public function test_admin_cannot_skip_status_transitions(): void
    {
        $app = AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'submitted', 'answers' => $this->answers(),
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/adoption-applications/{$app->id}/status", ['status' => 'adopted'])
            ->assertStatus(422);
    }
}
