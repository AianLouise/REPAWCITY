<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'details', 'image', 'date_published', 'is_featured', 'user_id'])]
class News extends Model
{
    /** @use HasFactory<\Database\Factories\NewsFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_published' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): string
    {
        return app(\App\Services\FileUploadService::class)->url('news', $this->image);
    }

    public function getThumbUrlAttribute(): string
    {
        return app(\App\Services\FileUploadService::class)->thumbUrl('news', $this->image);
    }

    public function getExcerptAttribute(): string
    {
        $max = strlen($this->title) > 50 ? 200 : 300;

        return mb_strlen($this->details) > $max
            ? mb_substr($this->details, 0, $max).'...'
            : $this->details;
    }
}
