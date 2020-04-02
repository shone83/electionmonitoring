<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $fillable = [
        'name', 
        'town_id', 
        'reon_id'
    ];

    public function town()
    {
        return $this->belongsTo('App\Town');
    }

    public function electoralUnit()
    {
        return $this->hasMany('App\ElectoralUnit');
    }
}
