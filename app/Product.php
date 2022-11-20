<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'added_by', 
        'tags', 
        'user_id', 
        'main_id',
        'category_id', 
        'subcategory_id', 
        'subsubcategory_id', 
        'subsubsubcategory_id', 
        'subsubsubsubcategory_id', 
        'subsubsubsubsubcategory_id', 
        'subsubsubsubsubsubcategory_id', 
        'brand_id', 
        'video_provider', 
        'video_link', 
        'unit_price',
        'purchase_price', 
        'unit', 
        'slug', 
        'colors', 
        'choice_options', 
        'variations', 
        'current_stock', 
        'made_in_nepal',
        'meta_title',
        'meta_description',
        'description',
        'extra_desc',
        'photos',
        'published',
        'thumbnail_img',
        'featured_img',
        'flash_deal_img',
        'discount',
        'discount_type'
      ];
    public function category(){
    	return $this->belongsTo(Category::class);
    }

    public function subcategory(){
    	return $this->belongsTo(SubCategory::class);
    }

    public function subsubcategory(){
    	return $this->belongsTo(SubSubCategory::class);
    }

    public function brand(){
    	return $this->belongsTo(Brand::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function orderDetails(){
    return $this->hasMany(OrderDetail::class);
    }

    public function reviews(){
    return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists(){
    return $this->hasMany(Wishlist::class);
    }

    public function stocks(){
    return $this->hasMany(ProductStock::class);
    }
}
