<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepublicList extends Model
{
    protected $fillable = [
        'name', 'minority', 'candidates_number'
    ];
}
