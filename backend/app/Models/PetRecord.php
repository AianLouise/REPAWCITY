<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pet_id', 'type', 'title', 'details', 'record_date', 'created_by'])]
class PetRecord extends Model
{
    /** @use HasFactory<\Database\Factories\PetRecordFactory> */
    use HasFactory;

    public const TYPE_VACCINATION = 'vaccination';
    public const TYPE_VET_VISIT = 'vet_visit';
    public const TYPE_GROOMING = 'grooming';
    public const TYPE_INTAKE = 'intake';
    public const TYPE_NOTE = 'note';

    public const TYPES = [
        self::TYPE_VACCINATION,
        self::TYPE_VET_VISIT,
        self::TYPE_GROOMING,
        self::TYPE_INTAKE,
        self::TYPE_NOTE,
    ];

    /**
     * Record types safe to expose publicly (non-sensitive).
     */
    public const PUBLIC_TYPES = [
        self::TYPE_VACCINATION,
        self::TYPE_GROOMING,
        self::TYPE_INTAKE,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'record_date' => 'date',
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
