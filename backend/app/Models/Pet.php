<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'type', 'breed', 'sex', 'weight', 'age', 'date', 'intake_date', 'intake_notes', 'microchip', 'about', 'image', 'is_featured', 'status', 'user_id'])]
class Pet extends Model
{
    /** @use HasFactory<\Database\Factories\PetFactory> */
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_ON_HOLD = 'on_hold';
    public const STATUS_ADOPTED = 'adopted';
    public const STATUS_DECEASED = 'deceased';

    public const STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_ON_HOLD,
        self::STATUS_ADOPTED,
        self::STATUS_DECEASED,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'intake_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(PetRecord::class);
    }

    /**
     * Pets the public can see / book: anything that is not adopted or deceased.
     */
    public function scopeAvailableForAdoption($query)
    {
        return $query->whereNotIn('status', [self::STATUS_ADOPTED, self::STATUS_DECEASED]);
    }

    public function getImageUrlAttribute(): string
    {
        return app(\App\Services\FileUploadService::class)->url('pets', $this->image);
    }

    public function getThumbUrlAttribute(): string
    {
        $service = app(\App\Services\FileUploadService::class);

        return $service->thumbExists('pets', $this->image)
            ? $service->thumbUrl('pets', $this->image)
            : $service->url('pets', $this->image);
    }
}
