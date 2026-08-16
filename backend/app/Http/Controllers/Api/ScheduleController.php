<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ShelterSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    /**
     * Public availability for the next 60 days: open/closed + remaining
     * capacity per session. Feeds the booking calendar.
     */
    public function index(Request $request): JsonResponse
    {
        $from = Carbon::today();
        $to = $from->copy()->addDays(60);

        $schedules = ShelterSchedule::whereBetween('date', [$from, $to])->get()->keyBy(fn ($s) => $s->date->toDateString());

        $booked = Appointment::query()
            ->whereBetween('appointment_date', [$from, $to])
            ->whereIn('status', ['Pending', 'Accepted'])
            ->selectRaw('appointment_date, time_slot, COUNT(*) as total')
            ->groupBy('appointment_date', 'time_slot')
            ->get()
            ->groupBy(fn ($a) => $a->appointment_date->toDateString());

        $days = collect();

        for ($d = $from->copy(); $d <= $to; $d->addDay()) {
            $key = $d->toDateString();
            $schedule = $schedules->get($key);

            $isOpen = $schedule?->is_open ?? true;
            $morningCapacity = $schedule?->morning_capacity ?? 10;
            $afternoonCapacity = $schedule?->afternoon_capacity ?? 10;

            $dayBooked = $booked->get($key);
            $morningBooked = $dayBooked?->firstWhere('time_slot', 'Morning Session')?->total ?? 0;
            $afternoonBooked = $dayBooked?->firstWhere('time_slot', 'Afternoon Session')?->total ?? 0;

            $days->push([
                'date' => $key,
                'is_open' => $isOpen,
                'reason' => $isOpen ? null : ($schedule?->reason ?? 'Shelter closed'),
                'morning_capacity' => $morningCapacity,
                'afternoon_capacity' => $afternoonCapacity,
                'morning_booked' => $morningBooked,
                'afternoon_booked' => $afternoonBooked,
                'morning_full' => $morningBooked >= $morningCapacity,
                'afternoon_full' => $afternoonBooked >= $afternoonCapacity,
                'fully_booked' => ! $isOpen || ($morningBooked >= $morningCapacity && $afternoonBooked >= $afternoonCapacity),
            ]);
        }

        return response()->json(['data' => $days->values()]);
    }

    /**
     * Admin: set open/closed + capacities for a single date.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'is_open' => ['required', 'boolean'],
            'morning_capacity' => ['required', 'integer', 'min:0', 'max:100'],
            'afternoon_capacity' => ['required', 'integer', 'min:0', 'max:100'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $schedule = ShelterSchedule::updateOrCreate(
            ['date' => $validated['date']],
            [
                'is_open' => $validated['is_open'],
                'morning_capacity' => $validated['morning_capacity'],
                'afternoon_capacity' => $validated['afternoon_capacity'],
                'reason' => $validated['reason'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Schedule updated successfully',
            'schedule' => [
                'id' => $schedule->id,
                'date' => $schedule->date->toDateString(),
                'is_open' => $schedule->is_open,
                'morning_capacity' => $schedule->morning_capacity,
                'afternoon_capacity' => $schedule->afternoon_capacity,
                'reason' => $schedule->reason,
            ],
        ]);
    }

    /**
     * Admin: list schedules within a month range (for the calendar editor).
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $schedules = ShelterSchedule::whereBetween('date', [$request->from, $request->to])
            ->orderBy('date')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'date' => $s->date->toDateString(),
                'is_open' => $s->is_open,
                'morning_capacity' => $s->morning_capacity,
                'afternoon_capacity' => $s->afternoon_capacity,
                'reason' => $s->reason,
            ]);

        return response()->json(['data' => $schedules->values()]);
    }
}
