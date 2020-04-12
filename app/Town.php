<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    protected $fillable = [
        'name', 
        'district_id', 
        'electoral_number',
        'councilor_number'
    ];

    public function district()
    {
        return $this->belongsTo('App\District');
    }

    public function settlements()
    {
        return $this->hasMany('App\Settlement');
    }

    public function electoralUnits()
    {
        return $this->hasMany('App\ElectoralUnit');
    }
}
