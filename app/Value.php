<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = [
      'temperature', 'energy', 'power', 'voltage', 'current', 'time', 'device_id'
    ];

    public function device()
    {
    	return $this->belongsTo('App\Device');
    }
}
