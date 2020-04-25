<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalList extends Model
{
    protected $fillable = [
        'name', 
        'town_id',
        'minority'
    ];

    public function town()
    {
        return $this->belongsTo('App\Town');
    }
}
