<?php

namespace Tests\Feature;

use App\Models\AdoptionApplication;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;
use App\Notifications\ApplicationStatusNotification;
use App\Notifications\AppointmentStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
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

    protected function appointment(): Appointment
    {
        return Appointment::create([
            'appointment_type' => 'Adopt',
            'pet_id' => $this->pet->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'time_slot' => 'Morning Session',
            'first_name' => 'Juan', 'last_name' => 'Cruz',
            'mobile_number' => '09171234567', 'home_address' => 'St',
            'email_address' => 'user@gmail.com',
            'status' => 'Pending', 'message' => 'pending', 'user_id' => $this->user->id,
        ]);
    }

    public function test_appointment_status_change_creates_notification(): void
    {
        Notification::fake();
        $appt = $this->appointment();

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/appointments/{$appt->id}/status", ['status' => 'Accepted'])
            ->assertOk();

        Notification::assertSentTo($this->user, AppointmentStatusNotification::class);
    }

    public function test_application_status_change_creates_notification(): void
    {
        Notification::fake();
        $app = AdoptionApplication::create([
            'pet_id' => $this->pet->id, 'user_id' => $this->user->id,
            'status' => 'submitted', 'answers' => ['housing' => 'x'],
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/adoption-applications/{$app->id}/status", ['status' => 'under_review'])
            ->assertOk();

        Notification::assertSentTo($this->user, ApplicationStatusNotification::class);
    }

    public function test_user_can_list_notifications_with_unread_count(): void
    {
        Mail::fake();
        $this->user->notify(new AppointmentStatusNotification($this->appointment(), 'Accepted'));
        $this->user->notify(new AppointmentStatusNotification($this->appointment(), 'Cancelled'));

        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('unread_count', 2)
            ->assertJsonPath('data.0.data.type', 'appointment.status');
    }

    public function test_user_can_mark_single_notification_read(): void
    {
        Mail::fake();
        $this->user->notify(new AppointmentStatusNotification($this->appointment(), 'Accepted'));
        $id = $this->user->notifications()->first()->id;

        $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/notifications/{$id}/read")
            ->assertOk();

        $this->assertNotNull($this->user->notifications()->first()->read_at);
    }

    public function test_user_can_mark_all_notifications_read(): void
    {
        Mail::fake();
        $this->user->notify(new AppointmentStatusNotification($this->appointment(), 'Accepted'));
        $this->user->notify(new AppointmentStatusNotification($this->appointment(), 'Cancelled'));

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/notifications/read-all')
            ->assertOk();

        $this->assertSame(0, $this->user->unreadNotifications()->count());
    }

    public function test_user_cannot_read_others_notification(): void
    {
        Mail::fake();
        $other = User::create([
            'fname' => 'Maria', 'lname' => 'Santos', 'email' => 'maria@test.com',
            'password' => Hash::make('1234'), 'user_type' => '2',
        ]);
        $other->notify(new AppointmentStatusNotification($this->appointment(), 'Accepted'));
        $id = $other->notifications()->first()->id;

        $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/notifications/{$id}/read")
            ->assertStatus(404);
    }

    public function test_notifications_require_auth(): void
    {
        $this->getJson('/api/notifications')
            ->assertStatus(401);
    }

    public function test_email_mailable_is_built_from_notification(): void
    {
        $appt = $this->appointment();
        $notification = new AppointmentStatusNotification($appt->fresh(), 'Accepted');

        $mail = $notification->toMail($this->user);

        $this->assertInstanceOf(\App\Mail\AppointmentStatusMail::class, $mail);
        $this->assertSame('rePaw City — Appointment Accepted', $mail->data['subject']);
        $this->assertSame($appt->id, $mail->data['appointment_id']);
        $this->assertSame('emails.appointment-status', $mail->data['template']);
    }
}
