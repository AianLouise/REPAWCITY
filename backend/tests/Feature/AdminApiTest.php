<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\News;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

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

        Pet::create([
            'name' => 'Alas', 'type' => 'Dog', 'breed' => 'Labrador Retriever',
            'sex' => 'Male', 'weight' => '5-10 lbs', 'age' => '6 months to 5 years',
            'date' => '2023-05-03', 'about' => 'Playful dog.', 'image' => 'a.jpg',
            'is_featured' => 0, 'user_id' => $this->admin->id,
        ]);

        News::create([
            'title' => 'First article', 'details' => 'Body', 'image' => 'n.jpg',
            'is_featured' => true, 'user_id' => $this->admin->id,
        ]);
    }

    // ---------- Authorization ----------

    public function test_admin_endpoints_forbidden_for_regular_user(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/dashboard')
            ->assertForbidden();

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/users')
            ->assertForbidden();
    }

    public function test_admin_endpoints_require_authentication(): void
    {
        $this->getJson('/api/admin/dashboard')->assertUnauthorized();
    }

    // ---------- Dashboard ----------

    public function test_dashboard_returns_counts_and_events(): void
    {
        Appointment::create([
            'appointment_type' => 'Adopt', 'appointment_date' => '2026-10-01',
            'time_slot' => 'Morning Session', 'first_name' => 'Juan',
            'middle_name' => '', 'last_name' => 'Cruz', 'mobile_number' => '0917',
            'home_address' => 'St', 'email_address' => 'j@test.com',
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);
        Appointment::create([
            'appointment_type' => 'Donate', 'appointment_date' => '2026-10-01',
            'time_slot' => 'Afternoon Session', 'first_name' => 'Juan',
            'middle_name' => '', 'last_name' => 'Cruz', 'mobile_number' => '0917',
            'home_address' => 'St', 'email_address' => 'j@test.com',
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/dashboard?date=2026-10-01')
            ->assertOk()
            ->assertJsonPath('counts.total', 2)
            ->assertJsonPath('counts.adopt', 1)
            ->assertJsonPath('counts.donate', 1)
            ->assertJsonCount(2, 'events')
            ->assertJsonPath('events.0.start', '2026-10-01');
    }

    public function test_daily_returns_appointments_for_slot(): void
    {
        Appointment::create([
            'appointment_type' => 'Adopt', 'appointment_date' => '2026-10-01',
            'time_slot' => 'Morning Session', 'first_name' => 'Juan',
            'middle_name' => '', 'last_name' => 'Cruz', 'mobile_number' => '0917',
            'home_address' => 'St', 'email_address' => 'j@test.com',
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/dashboard/daily?date=2026-10-01&time_slot=Morning%20Session')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.first_name', 'Juan');
    }

    // ---------- Appointment status ----------

    public function test_update_status_accepts_and_generates_message(): void
    {
        Mail::fake();
        $appt = Appointment::create([
            'appointment_type' => 'Adopt', 'appointment_date' => '2026-10-01',
            'time_slot' => 'Morning Session', 'first_name' => 'Juan',
            'middle_name' => '', 'last_name' => 'Cruz', 'mobile_number' => '0917',
            'home_address' => 'St', 'email_address' => 'j@test.com',
            'status' => 'Pending', 'message' => 'pending', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/appointments/{$appt->id}/status", ['status' => 'Accepted'])
            ->assertOk()
            ->assertJsonPath('appointment.status', 'Accepted')
            ->assertJsonPath('appointment.message', "Good Day, Ma'am/Sir,\n\nYour appointment is confirmed. Kindly message us within 24 hours if you would like to reschedule or cancel your appointment. Thank you!\n\nVery truly yours,\nRePaw City");
    }

    public function test_update_status_cancels_and_generates_message(): void
    {
        Mail::fake();
        $appt = Appointment::create([
            'appointment_type' => 'Adopt', 'appointment_date' => '2026-10-01',
            'time_slot' => 'Morning Session', 'first_name' => 'Juan',
            'middle_name' => '', 'last_name' => 'Cruz', 'mobile_number' => '0917',
            'home_address' => 'St', 'email_address' => 'j@test.com',
            'status' => 'Pending', 'message' => 'pending', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/appointments/{$appt->id}/status", ['status' => 'Cancelled'])
            ->assertOk()
            ->assertJsonPath('appointment.status', 'Cancelled')
            ->assertJsonPath('appointment.message', "Good Day, Ma'am/Sir,\n\nWe're sincerely sorry to cancel your appointment because of the sudden circumstances in our shelter. We hope for your consideration. Thank you.\n\nVery truly yours,\nRePaw City");
    }

    public function test_update_status_rejects_invalid_status(): void
    {
        $appt = Appointment::create([
            'appointment_type' => 'Adopt', 'appointment_date' => '2026-10-01',
            'time_slot' => 'Morning Session', 'first_name' => 'Juan',
            'middle_name' => '', 'last_name' => 'Cruz', 'mobile_number' => '0917',
            'home_address' => 'St', 'email_address' => 'j@test.com',
            'status' => 'Pending', 'message' => '', 'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/appointments/{$appt->id}/status", ['status' => 'Weird'])
            ->assertStatus(422);
    }

    // ---------- Pet CRUD ----------

    public function test_admin_can_create_pet_with_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin, 'sanctum')
            ->post('/api/admin/pets', [
                'name' => 'Buddy', 'type' => 'Dog', 'breed' => 'Golden Retriever',
                'sex' => 'Male', 'weight' => '10-20 lbs', 'age' => '6 months to 5 years',
                'date' => '2026-08-01', 'about' => 'Friendly dog.',
                'image' => UploadedFile::fake()->image('buddy.jpg'),
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Buddy');

        $this->assertDatabaseCount('pets', 2);
        Storage::disk('public')->assertExists('pets/'.Pet::latest('id')->first()->image);
    }

    public function test_admin_can_update_pet(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->putJson('/api/admin/pets/1', ['about' => 'Updated about.'])
            ->assertOk()
            ->assertJsonPath('data.about', 'Updated about.');
    }

    public function test_admin_can_delete_pet(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson('/api/admin/pets/1')
            ->assertOk();

        $this->assertDatabaseCount('pets', 0);
    }

    public function test_set_featured_assigns_slots(): void
    {
        Pet::create([
            'name' => 'Cookies', 'type' => 'Cat', 'breed' => 'British Shorthair',
            'sex' => 'Male', 'weight' => 'Less than 5 lbs', 'age' => '6 months to 5 years',
            'date' => '2023-05-08', 'about' => 'Cat.', 'image' => 'c.jpg',
            'is_featured' => 0, 'user_id' => $this->admin->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/pets/featured', [
                'featured_image_1' => 2,
                'featured_image_2' => 1,
                'featured_image_3' => 0,
                'featured_image_4' => 0,
            ])
            ->assertOk();

        $this->assertDatabaseHas('pets', ['id' => 2, 'is_featured' => 1]);
        $this->assertDatabaseHas('pets', ['id' => 1, 'is_featured' => 2]);
        $this->assertDatabaseMissing('pets', ['id' => 2, 'is_featured' => 0]);
    }

    // ---------- News CRUD ----------

    public function test_admin_can_create_news_with_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin, 'sanctum')
            ->post('/api/admin/news', [
                'title' => 'New article', 'details' => 'Body text.',
                'image' => UploadedFile::fake()->image('news.jpg'),
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'New article');

        $this->assertDatabaseCount('news', 2);
        Storage::disk('public')->assertExists('news/'.News::latest('id')->first()->image);
    }

    public function test_admin_can_update_news(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->putJson('/api/admin/news/1', ['title' => 'Updated title'])
            ->assertOk()
            ->assertJsonPath('data.title', 'Updated title');
    }

    public function test_admin_can_delete_news(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson('/api/admin/news/1')
            ->assertOk();

        $this->assertDatabaseCount('news', 0);
    }

    public function test_set_news_headline_clears_others(): void
    {
        News::create([
            'title' => 'Second', 'details' => 'Body', 'image' => 'n2.jpg',
            'is_featured' => false, 'user_id' => $this->admin->id,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/news/2/feature')
            ->assertOk()
            ->assertJsonPath('message', 'Set as Headline');

        $this->assertDatabaseHas('news', ['id' => 2, 'is_featured' => 1]);
        $this->assertDatabaseHas('news', ['id' => 1, 'is_featured' => 0]);
    }

    // ---------- User management ----------

    public function test_admin_can_list_users(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/users')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_can_update_user(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->putJson('/api/admin/users/'.$this->user->id, ['fname' => 'Juancito'])
            ->assertOk()
            ->assertJsonPath('data.fname', 'Juancito');
    }

    public function test_admin_can_promote_and_demote_user(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/users/'.$this->user->id.'/role', ['user_type' => '1'])
            ->assertOk()
            ->assertJsonPath('data.user_type', '1');

        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/users/'.$this->user->id.'/role', ['user_type' => '2'])
            ->assertOk()
            ->assertJsonPath('data.user_type', '2');
    }

    public function test_admin_can_delete_user(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson('/api/admin/users/'.$this->user->id)
            ->assertOk();

        $this->assertDatabaseCount('users', 1);
    }
}
