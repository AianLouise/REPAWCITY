<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Admin dashboard data (ported from admin/admin-dashboard.php):
     * stat counts, calendar events, and daily appointment tables.
     */
    public function index(Request $request): JsonResponse
    {
        $date = $request->input('date', now()->toDateString());

        $counts = [
            'total' => Appointment::count(),
            'adopt' => Appointment::where('appointment_type', 'Adopt')->count(),
            'donate' => Appointment::where('appointment_type', 'Donate')->count(),
            'visit' => Appointment::where('appointment_type', 'Visit')->count(),
            'volunteer' => Appointment::where('appointment_type', 'Volunteer')->count(),
        ];

        $events = Appointment::query()
            ->get(['appointment_date', 'time_slot'])
            ->map(fn (Appointment $appt) => [
                'start' => $appt->appointment_date->toDateString(),
                'title' => $appt->time_slot,
            ]);

        return response()->json([
            'counts' => $counts,
            'events' => $events,
            'date' => $date,
        ]);
    }

    /**
     * Appointment rows for a given date + time slot.
     * Used to fill the Morning/Afternoon session tables on the dashboard.
     */
    public function daily(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'date' => ['required', 'date'],
            'time_slot' => ['required', 'in:Morning Session,Afternoon Session'],
        ]);

        return AppointmentResource::collection(
            Appointment::query()
                ->whereDate('appointment_date', $request->date)
                ->where('time_slot', $request->time_slot)
                ->orderBy('id')
                ->get()
        );
    }

    /**
     * User dashboard overview: upcoming appointments, active applications,
     * favorite pet ids, and quick stats.
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        $upcoming = $user->appointments()
            ->with('pet')
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->orderBy('time_slot')
            ->get();

        $applications = $user->adoptionApplications()
            ->with('pet')
            ->whereIn('status', ['submitted', 'under_review', 'approved'])
            ->get();

        $favoriteIds = $user->favorites()->pluck('pet_id');

        return response()->json([
            'user' => [
                'fname' => $user->fname,
                'lname' => $user->lname,
                'email' => $user->email,
            ],
            'upcoming_appointments' => AppointmentResource::collection($upcoming),
            'active_applications' => $applications->map(fn ($a) => [
                'id' => $a->id,
                'pet' => [
                    'id' => $a->pet->id,
                    'name' => $a->pet->name,
                    'type' => $a->pet->type,
                    'image_url' => $a->pet->image_url,
                    'thumb_url' => $a->pet->thumb_url,
                ],
                'status' => $a->status,
            ]),
            'favorite_pet_ids' => $favoriteIds,
            'stats' => [
                'appointments' => $user->appointments()->count(),
                'applications' => $user->adoptionApplications()->count(),
                'favorites' => $favoriteIds->count(),
            ],
        ]);
    }
}
