<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['date', 'is_open', 'morning_capacity', 'afternoon_capacity', 'reason'])]
class ShelterSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\ShelterScheduleFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_open' => 'boolean',
        ];
    }
}
