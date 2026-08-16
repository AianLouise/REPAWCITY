<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\VolunteerShift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * List the authenticated volunteer's shifts.
     */
    public function my(Request $request): JsonResponse
    {
        $volunteer = Volunteer::where('user_id', $request->user()->id)->first();
        if ($volunteer === null) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $volunteer->shifts()->orderBy('date', 'desc')->get()]);
    }

    /**
     * Log hours for one of the volunteer's shifts.
     */
    public function logHours(Request $request, VolunteerShift $shift): JsonResponse
    {
        $volunteer = Volunteer::where('user_id', $request->user()->id)->first();
        abort_if($volunteer === null || $shift->volunteer_id !== $volunteer->id, 403);

        $validated = $request->validate([
            'hours_logged' => ['required', 'integer', 'min:0', 'max:24'],
        ]);

        DB::transaction(function () use ($shift, $validated) {
            $shift->update(['hours_logged' => $validated['hours_logged']]);
            $shift->volunteer->update([
                'total_hours' => $shift->volunteer->shifts()->sum('hours_logged'),
            ]);
        });

        return response()->json([
            'message' => 'Hours logged.',
            'shift' => $shift->fresh(),
        ]);
    }

    /**
     * Admin: assign a shift to a volunteer.
     */
    public function store(Request $request, Volunteer $volunteer): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time_slot' => ['required', 'in:Morning Session,Afternoon Session'],
            'activity' => ['nullable', 'string', 'max:500'],
        ]);

        $shift = $volunteer->shifts()->create($validated);

        return response()->json([
            'message' => 'Shift assigned.',
            'shift' => $shift,
        ], 201);
    }

    /**
     * Admin: update a shift (date/slot/activity/hours).
     */
    public function update(Request $request, VolunteerShift $shift): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
            'time_slot' => ['nullable', 'in:Morning Session,Afternoon Session'],
            'hours_logged' => ['nullable', 'integer', 'min:0', 'max:24'],
            'activity' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($shift, $validated) {
            $shift->update($validated);
            $shift->volunteer->update([
                'total_hours' => $shift->volunteer->shifts()->sum('hours_logged'),
            ]);
        });

        return response()->json([
            'message' => 'Shift updated.',
            'shift' => $shift->fresh(),
        ]);
    }
}
