<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $includeFull = $request->route()?->hasParameter('news');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'details' => $includeFull ? $this->details : $this->excerpt,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'date_published' => $this->date_published,
            'is_featured' => (bool) $this->is_featured,
        ];
    }
}
