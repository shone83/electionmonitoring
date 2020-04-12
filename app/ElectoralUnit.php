<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElectoralUnit extends Model
{
    protected $fillable = [ 
        'town_id', 
        'settlement_id', 
        'name', 
        'expected_result',
        'capillary'
    ];

    public function town()
    {
        return $this->belongsTo('App\Town');
    }

    public function settlement()
    {
        return $this->belongsTo('App\Settlement');
    }
}
