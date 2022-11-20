<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['country_id', 'name', 'status'];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }
}
