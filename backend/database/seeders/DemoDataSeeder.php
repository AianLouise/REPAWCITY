<?php

namespace Database\Seeders;

use App\Models\AdoptionApplication;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\Pet;
use App\Models\PetRecord;
use App\Models\ShelterSchedule;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerShift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Optional demo data so every screen has something to show.
 * Run with: php artisan db:seed --class=DemoDataSeeder
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- Regular demo users ---
        $demoUsers = [];
        $names = [
            ['Maria', 'Santos'], ['Jose', 'Reyes'], ['Liza', 'Garcia'],
            ['Carlos', 'Mendoza'], ['Angela', 'Torres'], ['Paolo', 'Aquino'],
            ['Rosa', 'Villanueva'], ['Migs', 'Ramos'], ['Bella', 'Domingo'],
            ['Ken', 'Flores'],
        ];
        foreach ($names as $i => [$fname, $lname]) {
            $demoUsers[] = User::firstOrCreate(
                ['email' => 'demo'.($i + 1).'@repawcity.com'],
                [
                    'fname' => $fname,
                    'lname' => $lname,
                    'password' => Hash::make('demo1234'),
                    'user_type' => '2',
                ]
            );
        }

        $admin = User::where('email', 'admin@gmail.com')->first();
        $pets = Pet::all();

        // --- Shelter schedules (close a few upcoming days) ---
        foreach ([2, 5, 9] as $offset) {
            ShelterSchedule::firstOrCreate(
                ['date' => now()->addDays($offset)->toDateString()],
                [
                    'is_open' => false,
                    'morning_capacity' => 10,
                    'afternoon_capacity' => 10,
                    'reason' => 'Shelter maintenance',
                ]
            );
        }

        // Bail out early if demo data already exists (idempotent re-run).
        $alreadySeeded = Appointment::whereHas('user', fn ($q) => $q->where('email', 'like', 'demo%@repawcity.com'))->exists();
        if ($alreadySeeded) {
            return;
        }

        // --- Appointments ---
        $appointmentTypes = ['Adopt', 'Donate', 'Visit', 'Volunteer'];
        $createdAppointments = [];
        foreach ($demoUsers as $i => $user) {
            $pet = $pets->get($i % max($pets->count(), 1));
            $appt = Appointment::create([
                'appointment_type' => $appointmentTypes[$i % 4],
                'pet_id' => $pet?->id,
                'appointment_date' => now()->addDays(($i % 10) + 3)->toDateString(),
                'time_slot' => $i % 2 === 0 ? 'Morning Session' : 'Afternoon Session',
                'first_name' => $user->fname,
                'last_name' => $user->lname,
                'mobile_number' => '0917'.str_pad((string) (1000000 + $i * 111111), 7, '0', STR_PAD_LEFT),
                'home_address' => $i % 2 === 0 ? 'Purok 3 Balsik, Hermosa, Bataan' : 'Tondo, Manila',
                'email_address' => $user->email,
                'status' => $i % 3 === 0 ? 'Accepted' : 'Pending',
                'message' => $i % 3 === 0
                    ? "Good Day, Ma'am/Sir,\n\nYour appointment is confirmed. Kindly message us within 24 hours if you would like to reschedule or cancel your appointment. Thank you!\n\nVery truly yours,\nRePaw City"
                    : '"Your appointment is currently pending approval."',
                'user_id' => $user->id,
            ]);
            $createdAppointments[] = $appt;
        }

        // --- Adoption applications ---
        foreach ($demoUsers as $i => $user) {
            $pet = $pets->get(($i * 2) % max($pets->count(), 1));
            AdoptionApplication::create([
                'pet_id' => $pet->id,
                'user_id' => $user->id,
                'appointment_id' => $createdAppointments[$i]->id ?? null,
                'status' => match ($i % 4) {
                    0 => 'under_review',
                    1 => 'approved',
                    2 => 'submitted',
                    default => 'rejected',
                },
                'answers' => [
                    'housing' => $i % 2 === 0 ? 'Owned home with a fenced yard.' : 'Apartment with a balcony.',
                    'other_pets' => 'One friendly cat.',
                    'experience' => 'Grew up around dogs.',
                    'why_this_pet' => 'Looking for a calm companion.',
                ],
                'notes' => $i % 4 === 1 ? 'Great applicant, schedule a home visit.' : null,
            ]);
        }

        // --- Donations ---
        foreach ($demoUsers as $i => $user) {
            if ($i % 2 === 0) {
                Donation::create([
                    'donor_name' => $user->fname.' '.$user->lname,
                    'donor_email' => $user->email,
                    'type' => 'cash',
                    'amount' => [250, 500, 1000, 1500][$i % 4],
                    'date' => now()->subDays($i % 20)->toDateString(),
                    'notes' => 'Supporting rePaw City!',
                    'user_id' => $user->id,
                ]);
            } else {
                Donation::create([
                    'donor_name' => $user->fname.' '.$user->lname,
                    'donor_email' => $user->email,
                    'type' => 'in_kind',
                    'item_description' => ['Dog food x5', 'Old blankets', 'Cat litter', 'Pet toys'][$i % 4],
                    'date' => now()->subDays($i % 20)->toDateString(),
                    'notes' => null,
                    'user_id' => $user->id,
                ]);
            }
        }

        // --- Volunteers + shifts ---
        foreach ($demoUsers as $i => $user) {
            $volunteer = Volunteer::create([
                'user_id' => $user->id,
                'availability' => ['Weekends Morning', 'Weekdays Afternoon'],
                'skills' => ['Dog walking', 'Cleaning', 'Photography'][$i % 3],
                'interests' => 'Helping animals',
                'status' => $i % 3 === 0 ? 'pending' : 'active',
                'total_hours' => 0,
            ]);

            if ($i % 3 !== 0) {
                $hours = [2, 3, 4][$i % 3];
                VolunteerShift::create([
                    'volunteer_id' => $volunteer->id,
                    'date' => now()->addDays($i % 14 + 1)->toDateString(),
                    'time_slot' => $i % 2 === 0 ? 'Morning Session' : 'Afternoon Session',
                    'hours_logged' => $hours,
                    'activity' => ['Kennel cleaning', 'Walking dogs', 'Adoption event'][$i % 3],
                ]);
                $volunteer->update(['total_hours' => $hours]);
            }
        }

        // --- Pet care records ---
        $recordTitles = [
            'vaccination' => ['Rabies booster', 'Distemper shot', '5-in-1 vaccine'],
            'grooming' => ['Bath & brush', 'Nail trim', 'Full groom'],
            'intake' => ['Shelter intake', 'Rescue intake'],
            'note' => ['Behavioral note', 'Diet update'],
            'vet_visit' => ['Routine checkup', 'Deworming'],
        ];
        foreach ($pets as $i => $pet) {
            $types = ['vaccination', 'grooming', 'intake', 'note', 'vet_visit'];
            $count = ($i % 3) + 1;
            for ($r = 0; $r < $count; $r++) {
                $type = $types[($i + $r) % count($types)];
                $titles = $recordTitles[$type];
                PetRecord::create([
                    'pet_id' => $pet->id,
                    'type' => $type,
                    'title' => $titles[$i % count($titles)],
                    'details' => 'Routine care recorded by the shelter team.',
                    'record_date' => now()->subDays(($i + $r) * 7 + 5)->toDateString(),
                    'created_by' => $admin?->id,
                ]);
            }
        }
    }
}
