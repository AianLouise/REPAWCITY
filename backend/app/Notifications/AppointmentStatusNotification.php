<?php

namespace App\Notifications;

use App\Mail\AppointmentStatusMail;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $status,
    ) {}

    /**
     * Payload shared by the database record and the email.
     *
     * @return array<string, mixed>
     */
    protected function payload(): array
    {
        $label = match ($this->status) {
            'Accepted' => 'Your appointment has been accepted!',
            'Cancelled' => 'Your appointment has been cancelled.',
            default => 'Your appointment status changed to '.$this->status.'.',
        };

        return [
            'type' => 'appointment.status',
            'title' => $label,
            'message' => $this->appointment->message,
            'appointment_id' => $this->appointment->id,
            'appointment_type' => $this->appointment->appointment_type,
            'appointment_date' => $this->appointment->appointment_date?->toDateString(),
            'time_slot' => $this->appointment->time_slot,
            'status' => $this->status,
        ];
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->payload();
    }

    public function toMail(object $notifiable): AppointmentStatusMail
    {
        return new AppointmentStatusMail([
            ...$this->payload(),
            'subject' => $this->status === 'Accepted' ? 'rePaw City — Appointment Accepted' : 'rePaw City — Appointment Cancelled',
            'template' => 'emails.appointment-status',
        ]);
    }
}
