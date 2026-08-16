<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'breed' => $this->breed,
            'sex' => $this->sex,
            'weight' => $this->weight,
            'age' => $this->age,
            'date' => $this->date?->toDateString(),
            'about' => $this->about,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'thumb_url' => $this->thumb_url,
            'is_featured' => (int) $this->is_featured,
            'status' => $this->status,
            'intake_date' => $this->intake_date?->toDateString(),
            'intake_notes' => $this->intake_notes,
            'microchip' => $this->microchip,
        ];
    }
}
