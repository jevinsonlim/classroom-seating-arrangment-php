<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $casts = [
        'is_occupied_on_template' => 'boolean'
    ];
}
