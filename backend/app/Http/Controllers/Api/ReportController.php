<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdoptionApplication;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\VolunteerShift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Monthly aggregates for the last 12 months + top-pet interest.
     */
    public function index(Request $request): JsonResponse
    {
        $months = (int) $request->integer('months', 12);
        $months = min(max($months, 3), 24);

        $from = Carbon::today()->startOfMonth()->subMonths($months - 1);

        // Fetch raw rows and group by month in PHP so it works on both
        // MySQL and sqlite (DATE_FORMAT / strftime differ per driver).
        $appointments = Appointment::query()
            ->where('created_at', '>=', $from)
            ->get(['created_at'])
            ->groupBy(fn ($a) => $a->created_at->format('Y-m'))
            ->map->count();

        $applications = AdoptionApplication::query()
            ->where('created_at', '>=', $from)
            ->get(['created_at'])
            ->groupBy(fn ($a) => $a->created_at->format('Y-m'))
            ->map->count();

        $adoptions = AdoptionApplication::query()
            ->where('status', 'adopted')
            ->where('updated_at', '>=', $from)
            ->get(['updated_at'])
            ->groupBy(fn ($a) => $a->updated_at->format('Y-m'))
            ->map->count();

        $donationRows = Donation::query()
            ->where('date', '>=', $from)
            ->get(['date', 'type', 'amount'])
            ->groupBy(fn ($d) => $d->date->format('Y-m'));

        $volunteerHours = VolunteerShift::query()
            ->where('date', '>=', $from)
            ->get(['date', 'hours_logged'])
            ->groupBy(fn ($s) => $s->date->format('Y-m'));

        // Build the monthly series.
        $series = [];
        for ($i = 0; $i < $months; $i++) {
            $key = $from->copy()->addMonths($i)->format('Y-m');

            $monthDonations = $donationRows->get($key, collect());
            $cashTotal = $monthDonations->where('type', 'cash')->sum('amount');

            $series[] = [
                'month' => $key,
                'label' => $from->copy()->addMonths($i)->format('M Y'),
                'appointments' => (int) ($appointments[$key] ?? 0),
                'applications' => (int) ($applications[$key] ?? 0),
                'adoptions' => (int) ($adoptions[$key] ?? 0),
                'donations_cash' => (float) $cashTotal,
                'donations_in_kind' => (int) $monthDonations->where('type', 'in_kind')->count(),
                'volunteer_hours' => (int) (($volunteerHours[$key] ?? collect())->sum('hours_logged') ?? 0),
            ];
        }

        // Top pets by interest (appointments + applications referencing them).
        $topPets = Appointment::query()
            ->whereNotNull('pet_id')
            ->with('pet')
            ->selectRaw('pet_id, COUNT(*) as count')
            ->groupBy('pet_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'pet_id' => $a->pet_id,
                'name' => $a->pet->name ?? 'Unknown',
                'appointments' => $a->count,
            ])
            ->toArray();

        $topApplicationPets = AdoptionApplication::query()
            ->with('pet')
            ->selectRaw('pet_id, COUNT(*) as count')
            ->groupBy('pet_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'pet_id' => $a->pet_id,
                'name' => $a->pet->name ?? 'Unknown',
                'applications' => $a->count,
            ])
            ->toArray();

        return response()->json([
            'months' => $months,
            'series' => $series,
            'totals' => [
                'appointments' => array_sum(array_column($series, 'appointments')),
                'applications' => array_sum(array_column($series, 'applications')),
                'adoptions' => array_sum(array_column($series, 'adoptions')),
                'donations_cash' => round(array_sum(array_column($series, 'donations_cash')), 2),
                'volunteer_hours' => array_sum(array_column($series, 'volunteer_hours')),
            ],
            'top_pets_by_appointments' => $topPets,
            'top_pets_by_applications' => $topApplicationPets,
        ]);
    }
}
