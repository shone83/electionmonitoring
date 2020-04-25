<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turnout extends Model
{
    protected $fillable = [
        'name', 
        'town_id'
    ];

    protected $casts = [
        'turnout_by_hour' => 'date:hh:mm'
    ];
}
