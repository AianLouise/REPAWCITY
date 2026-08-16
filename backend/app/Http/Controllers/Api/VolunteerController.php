<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    /**
     * Submit a volunteer application (auth).
     */
    public function apply(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'availability' => ['nullable', 'array'],
            'availability.*' => ['string', 'max:50'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'interests' => ['nullable', 'string', 'max:1000'],
        ]);

        $existing = Volunteer::where('user_id', $request->user()->id)->first();
        if ($existing) {
            return response()->json([
                'message' => 'You have already submitted a volunteer application.',
            ], 409);
        }

        $volunteer = Volunteer::create([
            'user_id' => $request->user()->id,
            'availability' => $validated['availability'] ?? [],
            'skills' => $validated['skills'] ?? null,
            'interests' => $validated['interests'] ?? null,
            'status' => Volunteer::STATUS_PENDING,
            'total_hours' => 0,
        ]);

        return response()->json([
            'message' => 'Volunteer application submitted. We will review it soon!',
            'volunteer' => $volunteer->load('user'),
        ], 201);
    }

    /**
     * Show the authenticated user's volunteer profile.
     */
    public function my(Request $request): JsonResponse
    {
        $volunteer = Volunteer::with('shifts')
            ->where('user_id', $request->user()->id)
            ->first();

        return response()->json(['data' => $volunteer]);
    }

    /**
     * Admin: list volunteers.
     */
    public function index(Request $request): JsonResponse
    {
        $volunteers = Volunteer::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        return response()->json(['data' => $volunteers]);
    }

    /**
     * Admin: approve or deactivate a volunteer.
     */
    public function updateStatus(Request $request, Volunteer $volunteer): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Volunteer::STATUSES)],
        ]);

        $volunteer->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Volunteer status updated.',
            'volunteer' => $volunteer->fresh()->load('user'),
        ]);
    }
}
