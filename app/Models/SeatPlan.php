<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatPlan extends Model
{
    public function section() : BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function seats() : HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function prepopulateSeats($template = null): void
    {
        for ($i=1; $i <= $this->rows; $i++) { 
            for ($j=1; $j <= $this->columns; $j++) { 
                $seat = $this->seats()->create([
                    'row' => $i,
                    'column' => $j,
                ]);
            }
        }
    }
}