<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
//    public $incrementing = false;

	protected $fillable = ['unique_id', 'user_id', 'name'];

    public function owner()
    {
    	return $this->belongsTo('App\User');
    }

    public function values()
    {
    	return $this->hasMany('App\Value');
    }
}
