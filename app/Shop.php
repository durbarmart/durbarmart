<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
  protected $fillable = ['user_id','name','slug'];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
