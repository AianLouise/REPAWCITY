<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdoptionApplicationRequest;
use App\Http\Resources\AdoptionApplicationResource;
use App\Models\AdoptionApplication;
use App\Models\Pet;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class AdoptionApplicationController extends Controller
{
    /**
     * Submit an adoption application for a pet (auth). One active application
     * per user per pet; pet must be available or on hold.
     */
    public function store(StoreAdoptionApplicationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        $existing = AdoptionApplication::where('pet_id', $data['pet_id'])
            ->where('user_id', $user->id)
            ->whereIn('status', AdoptionApplication::ACTIVE_STATUSES)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You already have an active application for this pet.',
            ], 409);
        }

        $pet = Pet::find($data['pet_id']);
        if ($pet === null || ! in_array($pet->status, [Pet::STATUS_AVAILABLE, Pet::STATUS_ON_HOLD])) {
            return response()->json([
                'message' => 'This pet is not available for adoption.',
            ], 422);
        }

        $application = AdoptionApplication::create([
            'pet_id' => $data['pet_id'],
            'user_id' => $user->id,
            'appointment_id' => $data['appointment_id'] ?? null,
            'status' => AdoptionApplication::STATUS_SUBMITTED,
            'answers' => $data['answers'],
        ]);

        return (new AdoptionApplicationResource($application->load('pet')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * List the authenticated user's applications.
     */
    public function my(Request $request): AnonymousResourceCollection
    {
        return AdoptionApplicationResource::collection(
            $request->user()
                ->adoptionApplications()
                ->with('pet')
                ->latest()
                ->get()
        );
    }

    /**
     * Withdraw a submitted/under-review application (auth).
     */
    public function cancel(Request $request, AdoptionApplication $application): JsonResponse
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        if (! in_array($application->status, [AdoptionApplication::STATUS_SUBMITTED, AdoptionApplication::STATUS_UNDER_REVIEW])) {
            return response()->json([
                'message' => 'This application can no longer be withdrawn.',
            ], 422);
        }

        $application->update(['status' => AdoptionApplication::STATUS_REJECTED]);

        return response()->json([
            'message' => 'Application withdrawn.',
            'application' => new AdoptionApplicationResource($application->fresh()->load('pet')),
        ]);
    }

    /**
     * Admin: list all applications for the pipeline board.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $applications = AdoptionApplication::with(['pet', 'user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        return AdoptionApplicationResource::collection($applications);
    }

    /**
     * Admin: move an application through the pipeline + optional staff notes.
     */
    public function updateStatus(Request $request, AdoptionApplication $application): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,submitted,under_review,approved,adopted,rejected'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $newStatus = $validated['status'];

        // Guard the happy-path transitions.
        $allowed = match ($application->status) {
            'submitted' => ['under_review', 'rejected'],
            'under_review' => ['approved', 'rejected'],
            'approved' => ['adopted', 'rejected'],
            default => [],
        };

        if (! in_array($newStatus, $allowed, true)) {
            return response()->json([
                'message' => 'Invalid status transition.',
            ], 422);
        }

        DB::transaction(function () use ($application, $newStatus, $validated) {
            $application->update([
                'status' => $newStatus,
                'notes' => $validated['notes'] ?? $application->notes,
            ]);

            // When an application is adopted, move the pet to adopted too.
            if ($newStatus === AdoptionApplication::STATUS_ADOPTED) {
                $application->pet->update(['status' => Pet::STATUS_ADOPTED]);
            }
        });

        // Notify the applicant in-app + by email.
        if ($application->user) {
            $application->user->notify(new ApplicationStatusNotification($application->fresh()->load('pet'), $newStatus));
        }

        return response()->json([
            'message' => 'Application status updated successfully.',
            'application' => new AdoptionApplicationResource($application->fresh()->load('pet')),
        ]);
    }
}
