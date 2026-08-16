<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\ShelterSchedule;
use App\Notifications\AppointmentStatusNotification;
use App\Services\AppointmentCapacityService;
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
    public function slots(Request $request, AppointmentCapacityService $capacity): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $date = $request->date;
        $booked = Appointment::query()
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['Pending', 'Accepted'])
            ->pluck('time_slot');

        $schedule = ShelterSchedule::whereDate('date', $date)->first();
        $isOpen = $schedule?->is_open ?? true;
        $morningCapacity = $schedule?->morning_capacity ?? AppointmentCapacityService::DEFAULT_CAPACITY;
        $afternoonCapacity = $schedule?->afternoon_capacity ?? AppointmentCapacityService::DEFAULT_CAPACITY;

        $morningFull = ! $capacity->isSlotAvailable($date, AppointmentCapacityService::SESSION_MORNING, $schedule);
        $afternoonFull = ! $capacity->isSlotAvailable($date, AppointmentCapacityService::SESSION_AFTERNOON, $schedule);

        return response()->json([
            'date' => $date,
            'is_open' => $isOpen,
            'reason' => $isOpen ? null : ($schedule?->reason ?? 'Shelter closed'),
            'morning_capacity' => $morningCapacity,
            'afternoon_capacity' => $afternoonCapacity,
            'booked' => $booked->values(),
            'morning_full' => $morningFull,
            'afternoon_full' => $afternoonFull,
            'fully_booked' => ! $isOpen || ($morningFull && $afternoonFull),
        ]);
    }

    /**
     * Store a booked appointment (replaces the 6-step session wizard insert
     * from booking/book-appointment5.php).
     *
     * The slot is re-checked inside a transaction with a row lock so two
     * concurrent requests cannot double-book the same date + time slot.
     */
    public function store(StoreAppointmentRequest $request, AppointmentCapacityService $capacity): JsonResponse
    {
        $data = $request->validated();

        $appointment = DB::transaction(function () use ($request, $data, $capacity) {
            // Lock the schedule row for this date (if any) to serialize capacity checks.
            $schedule = ShelterSchedule::whereDate('date', $data['appointment_date'])->lockForUpdate()->first();

            $isOpen = $schedule?->is_open ?? true;
            if (! $isOpen) {
                return null;
            }

            $sessionCapacity = match ($data['time_slot']) {
                AppointmentCapacityService::SESSION_MORNING => $schedule?->morning_capacity ?? AppointmentCapacityService::DEFAULT_CAPACITY,
                AppointmentCapacityService::SESSION_AFTERNOON => $schedule?->afternoon_capacity ?? AppointmentCapacityService::DEFAULT_CAPACITY,
                default => 0,
            };

            // Count existing confirmed bookings for the slot (Pending + Accepted).
            $bookedCount = Appointment::query()
                ->whereDate('appointment_date', $data['appointment_date'])
                ->where('time_slot', $data['time_slot'])
                ->whereIn('status', ['Pending', 'Accepted'])
                ->lockForUpdate()
                ->count();

            if ($sessionCapacity <= 0 || $bookedCount >= $sessionCapacity) {
                return null;
            }

            // Re-check pet availability under the same lock (race-safe).
            if (isset($data['pet_id'])) {
                $pet = Pet::query()->lockForUpdate()->find($data['pet_id']);
                if ($pet === null || ! in_array($pet->status, [Pet::STATUS_AVAILABLE, Pet::STATUS_ON_HOLD])) {
                    return null;
                }
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
                'message' => 'The selected date and time slot are unavailable. Please choose another slot or day.',
            ], 409);
        }

        return (new AppointmentResource($appointment->load('pet')))
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
            $request->user()->appointments()->with('pet')->latest()->get()
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

        // Notify the appointment owner in-app + by email.
        if ($appointment->user) {
            $appointment->user->notify(new AppointmentStatusNotification($appointment->fresh(), $status));
        }

        return response()->json([
            'message' => 'Status updated successfully',
            'appointment' => new AppointmentResource($appointment),
        ]);
    }
}
