<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubSubSubCategory extends Model
{
    //
    protected $fillable = [
        'name','sub_sub_category_id','slug','meta_title','meta_description'
      ];
}
