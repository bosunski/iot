<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $incrementing = false;

    public function owner()
    {
    	return $this->belongsTo('App\User');
    }
}
