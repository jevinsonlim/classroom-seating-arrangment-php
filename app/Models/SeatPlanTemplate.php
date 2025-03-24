<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatPlanTemplate extends Model
{
    public function seats(): HasMany
    {
        return $this->hasMany(SeatPlanTemplateSeat::class);
    }

    public function seatPlans(): HasMany
    {
        return $this->hasMany(SeatPlan::class);
    }
}
