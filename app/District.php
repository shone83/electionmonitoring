<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'name'
    ];

    public function town()
    {
        return $this->hasMany('App\Town');
    }
}
