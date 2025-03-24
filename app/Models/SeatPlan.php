<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

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

    public function logs() : HasMany
    {
        return $this->hasMany(SeatPlanLog::class);
    }

    public function prepopulateSeats(): void
    {
        for ($i=1; $i <= $this->rows; $i++) { 
            for ($j=1; $j <= $this->columns; $j++) { 
                $seat = Seat::create([
                    'seat_plan_id' => $this->id,
                    'row' => $i,
                    'column' => $j,
                ]);
            }
        }

        if ($this->template) {
            $occupiedSeats = $this->template->seats()->where('is_occupied', true)->get();

            foreach ($occupiedSeats as $seat) {
                Seat::query()
                    ->where('seat_plan_id', $this->id)
                    ->where('column', $seat->column)
                    ->where('row', $seat->row)
                    ->update(['is_occupied_on_template' => true]);
            }
        }
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SeatPlanTemplate::class,  'seat_plan_template_id');
    }
}