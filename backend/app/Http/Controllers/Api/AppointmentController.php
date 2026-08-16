<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Return which time slots are already booked for a given date.
     * Feeds the booking calendar (replaces the inline FullCalendar events).
     */
    public function slots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $booked = Appointment::query()
            ->whereDate('appointment_date', $request->date)
            ->pluck('time_slot');

        return response()->json([
            'date' => $request->date,
            'booked' => $booked,
        ]);
    }

    /**
     * Store a booked appointment (replaces the 6-step session wizard insert
     * from booking/book-appointment5.php).
     *
     * The slot is re-checked inside a transaction with a row lock so two
     * concurrent requests cannot double-book the same date + time slot.
     */
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $appointment = DB::transaction(function () use ($request, $data) {
            $conflict = Appointment::query()
                ->whereDate('appointment_date', $data['appointment_date'])
                ->where('time_slot', $data['time_slot'])
                ->lockForUpdate()
                ->exists();

            if ($conflict) {
                return null;
            }

            return Appointment::create([
                ...$data,
                'status' => 'Pending',
                'message' => '"Your appointment is currently pending approval."',
                'user_id' => $request->user()->id,
            ]);
        });

        if ($appointment === null) {
            return response()->json([
                'message' => 'The selected date and time slot are already booked. Please choose another slot.',
            ], 409);
        }

        return (new AppointmentResource($appointment))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * List all appointments for the authenticated user
     * (feeds the Profile dropdown + user/notification.php replacement).
     */
    public function myAppointments(Request $request): AnonymousResourceCollection
    {
        return AppointmentResource::collection(
            $request->user()->appointments()->latest()->get()
        );
    }

    /**
     * Return the notification message for an appointment, only if it belongs
     * to the authenticated user (replaces user/notification.php, fixes the
     * original SQL-injection-prone GET parameter).
     */
    public function message(Request $request, Appointment $appointment): JsonResponse
    {
        abort_unless($appointment->user_id === $request->user()->id, 403);

        return response()->json([
            'id' => $appointment->id,
            'appointment_type' => $appointment->appointment_type,
            'appointment_date' => $appointment->appointment_date?->toDateString(),
            'time_slot' => $appointment->time_slot,
            'status' => $appointment->status,
            'message' => $appointment->message,
        ]);
    }

    /**
     * Accept or cancel an appointment as an admin. Auto-generates the user
     * notification message (ported from admin/update_status.php).
     */
    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment): JsonResponse
    {
        $status = $request->validated('status');

        $message = match ($status) {
            'Accepted' => "Good Day, Ma'am/Sir,\n\nYour appointment is confirmed. Kindly message us within 24 hours if you would like to reschedule or cancel your appointment. Thank you!\n\nVery truly yours,\nRePaw City",
            'Cancelled' => "Good Day, Ma'am/Sir,\n\nWe're sincerely sorry to cancel your appointment because of the sudden circumstances in our shelter. We hope for your consideration. Thank you.\n\nVery truly yours,\nRePaw City",
            default => $appointment->message,
        };

        $appointment->update(['status' => $status, 'message' => $message]);

        return response()->json([
            'message' => 'Status updated successfully',
            'appointment' => new AppointmentResource($appointment),
        ]);
    }
}
