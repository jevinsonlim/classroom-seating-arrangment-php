<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatPlanTemplate extends Model
{
    public function seats(): HasMany
    {
        return $this->hasMany(SeatPlanTemplateSeat::class, 'seat_plan_template_id', 'id');
    }

    public function seatPlans(): HasMany
    {
        return $this->hasMany(SeatPlan::class);
    }
}
