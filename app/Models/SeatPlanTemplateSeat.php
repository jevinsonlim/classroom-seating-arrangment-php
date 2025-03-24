<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatPlanTemplateSeat extends Model
{
    protected $casts = [
        'is_occupied' => 'boolean'
    ];
}
