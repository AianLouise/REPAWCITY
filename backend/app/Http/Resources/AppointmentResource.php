<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_type' => $this->appointment_type,
            'pet' => $this->whenLoaded('pet') ? new PetResource($this->pet) : null,
            'appointment_date' => $this->appointment_date?->toDateString(),
            'time_slot' => $this->time_slot,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'mobile_number' => $this->mobile_number,
            'home_address' => $this->home_address,
            'email_address' => $this->email_address,
            'status' => $this->status,
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
