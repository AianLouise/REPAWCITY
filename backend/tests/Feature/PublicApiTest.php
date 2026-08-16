<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\News;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::create([
            'fname' => 'Aian Louise',
            'lname' => 'Alfaro',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'user_type' => '1',
        ]);

        Pet::create([
            'name' => 'Alas', 'type' => 'Dog', 'breed' => 'Labrador Retriever',
            'sex' => 'Male', 'weight' => '5-10 lbs', 'age' => '6 months to 5 years',
            'date' => '2023-05-03', 'about' => 'Playful dog.', 'image' => 'a.jpg',
            'is_featured' => 0, 'user_id' => $user->id,
        ]);
        Pet::create([
            'name' => 'Cookies', 'type' => 'Cat', 'breed' => 'British Shorthair',
            'sex' => 'Male', 'weight' => 'Less than 5 lbs', 'age' => '6 months to 5 years',
            'date' => '2023-05-08', 'about' => 'Playful cat.', 'image' => 'c.jpg',
            'is_featured' => 1, 'user_id' => $user->id,
        ]);

        News::create([
            'title' => str_repeat('Long Headline ', 6),
            'details' => str_repeat('Details text. ', 200),
            'image' => 'n.jpg',
            'is_featured' => true,
            'user_id' => $user->id,
        ]);
    }

    public function test_pets_list_returns_all_pets(): void
    {
        $this->getJson('/api/pets')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonStructure(['data' => [['id', 'name', 'image_url', 'is_featured']]]);
    }

    public function test_pets_list_filters_by_type(): void
    {
        $this->getJson('/api/pets?type=Cat')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Cookies');
    }

    public function test_pets_list_filters_by_multiple_criteria(): void
    {
        $this->getJson('/api/pets?sex=Male&age=6%20months%20to%205%20years')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_pets_featured_returns_in_slot_order(): void
    {
        Pet::create([
            'name' => 'Yuchi', 'type' => 'Dog', 'breed' => 'German Shepherd',
            'sex' => 'Male', 'weight' => '5-10 lbs', 'age' => 'Less than 6 months',
            'date' => '2023-05-12', 'about' => 'Smart dog.', 'image' => 'y.jpg',
            'is_featured' => 2, 'user_id' => 1,
        ]);

        $this->getJson('/api/pets?featured=1')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'Cookies')
            ->assertJsonPath('data.1.name', 'Yuchi')
            ->assertJsonPath('data.0.is_featured', 1)
            ->assertJsonPath('data.1.is_featured', 2);
    }

    public function test_pet_show_returns_single_pet(): void
    {
        $this->getJson('/api/pets/1')
            ->assertOk()
            ->assertJsonPath('data.name', 'Alas')
            ->assertJsonPath('data.image_url', '/storage/pets/a.jpg');
    }

    public function test_pet_show_returns_404_for_missing(): void
    {
        $this->getJson('/api/pets/999')->assertNotFound();
    }

    public function test_news_list_returns_excerpt_not_full_details(): void
    {
        $response = $this->getJson('/api/news');
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure(['data' => [['id', 'title', 'details', 'image_url', 'date_published', 'is_featured']]]);

        $details = $response->json('data.0.details');
        $this->assertTrue(strlen($details) <= 303, "Excerpt too long: ".strlen($details));
    }

    public function test_news_featured_returns_only_headline(): void
    {
        News::create([
            'title' => 'Second article', 'details' => 'Body', 'image' => 'n2.jpg',
            'is_featured' => false, 'user_id' => 1,
        ]);

        $this->getJson('/api/news?featured=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.is_featured', true);
    }

    public function test_news_show_returns_full_details(): void
    {
        $response = $this->getJson('/api/news/1');
        $response->assertOk()
            ->assertJsonPath('data.is_featured', true);

        $details = $response->json('data.details');
        $this->assertTrue(strlen($details) > 500, "Full details expected, got length ".strlen($details));
    }

    public function test_slots_returns_booked_slots_for_date(): void
    {
        Appointment::create([
            'appointment_type' => 'Adopt', 'appointment_date' => '2026-08-20',
            'time_slot' => 'Morning Session', 'first_name' => 'Juan',
            'middle_name' => '', 'last_name' => 'Cruz', 'mobile_number' => '09171234567',
            'home_address' => '123 St', 'email_address' => 'j@test.com',
            'status' => 'Pending', 'message' => '', 'user_id' => 1,
        ]);

        $this->getJson('/api/appointments/slots?date=2026-08-20')
            ->assertOk()
            ->assertJsonPath('booked.0', 'Morning Session');

        $this->getJson('/api/appointments/slots?date=2026-08-21')
            ->assertOk()
            ->assertJsonCount(0, 'booked');
    }

    public function test_slots_requires_date(): void
    {
        $this->getJson('/api/appointments/slots')->assertStatus(422);
    }
}
