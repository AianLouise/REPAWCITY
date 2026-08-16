<?php

namespace App\Notifications;

use App\Mail\ApplicationStatusMail;
use App\Models\AdoptionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public AdoptionApplication $application,
        public string $status,
    ) {}

    protected function payload(): array
    {
        $label = match ($this->status) {
            'under_review' => 'Your adoption application is now under review.',
            'approved' => 'Congratulations! Your adoption application was approved.',
            'adopted' => 'Your adoption is complete. Congratulations!',
            'rejected' => "We're sorry, your adoption application was not approved.",
            default => 'Your adoption application status changed to '.$this->status.'.',
        };

        return [
            'type' => 'application.status',
            'title' => $label,
            'message' => $this->application->notes ?? 'No additional notes from our team.',
            'application_id' => $this->application->id,
            'pet_name' => $this->application->pet?->name,
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

    public function toMail(object $notifiable): ApplicationStatusMail
    {
        return new ApplicationStatusMail([
            ...$this->payload(),
            'subject' => 'rePaw City — Adoption Application Update',
            'template' => 'emails.application-status',
        ]);
    }
}
