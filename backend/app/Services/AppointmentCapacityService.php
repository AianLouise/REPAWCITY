<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ShelterSchedule;

/**
 * Determines whether a date + session can accept another appointment.
 * Used by the booking wizard (slots) and the store endpoint (under lock).
 */
class AppointmentCapacityService
{
    public const DEFAULT_CAPACITY = 10;

    public const SESSION_MORNING = 'Morning Session';

    public const SESSION_AFTERNOON = 'Afternoon Session';

    /**
     * @return array{is_open: bool, morning_capacity: int, afternoon_capacity: int, reason: string|null}
     */
    public function scheduleFor(string $date): array
    {
        $schedule = ShelterSchedule::whereDate('date', $date)->first();

        return [
            'is_open' => $schedule?->is_open ?? true,
            'morning_capacity' => $schedule?->morning_capacity ?? self::DEFAULT_CAPACITY,
            'afternoon_capacity' => $schedule?->afternoon_capacity ?? self::DEFAULT_CAPACITY,
            'reason' => $schedule?->reason,
        ];
    }

    public function bookedCount(string $date, string $timeSlot): int
    {
        return Appointment::query()
            ->whereDate('appointment_date', $date)
            ->where('time_slot', $timeSlot)
            ->whereIn('status', ['Pending', 'Accepted'])
            ->count();
    }

    public function isSlotAvailable(string $date, string $timeSlot, ?ShelterSchedule $schedule = null): bool
    {
        $schedule ??= ShelterSchedule::whereDate('date', $date)->first();

        $capacity = match ($timeSlot) {
            self::SESSION_MORNING => $schedule?->morning_capacity ?? self::DEFAULT_CAPACITY,
            self::SESSION_AFTERNOON => $schedule?->afternoon_capacity ?? self::DEFAULT_CAPACITY,
            default => 0,
        };

        if (($schedule !== null && ! $schedule->is_open) || $capacity <= 0) {
            return false;
        }

        $booked = $this->bookedCount($date, $timeSlot);

        return $booked < $capacity;
    }
}
