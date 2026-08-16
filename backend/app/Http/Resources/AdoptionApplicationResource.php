<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdoptionApplicationResource extends JsonResource
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
            'pet' => new PetResource($this->pet),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'fname' => $this->user->fname,
                'lname' => $this->user->lname,
                'email' => $this->user->email,
            ]),
            'appointment_id' => $this->appointment_id,
            'status' => $this->status,
            'answers' => $this->answers,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
