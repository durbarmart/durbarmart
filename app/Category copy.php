<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'parent',
        'commision_rate',
        'banner',
        'icon',
        'featured',
        'top',
        'digital',
        'slug',
        'meta_title',
        'meta_description'
      ];

    public function subcategories(){
    	return $this->hasMany(SubCategory::class);
    }

    public function products(){
    	return $this->hasMany(Product::class);
    }

    public function classified_products(){
    	return $this->hasMany(CustomerProduct::class);
    }
}
