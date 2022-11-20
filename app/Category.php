<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'parent',
        'brand_id',
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

    // public function subcategories(){
    // 	return $this->hasMany(SubCategory::class);
    // }

    public function category(){
    	return $this->hasOne(self::class,'id','parent');
    }
    public function products(){
    	return $this->hasMany(Product::class);
    }

    public function classified_products(){
    	return $this->hasMany(CustomerProduct::class);
    }
    
    public function parentItem(){
    	return $this->hasOne(self::class,'id','parent');
    }
    public function parentItemWithParent(){
    	return $this->hasOne(self::class,'id','parent')->with('parentItemWithParent');
    }
    public function parentCat(){
    	return $this->hasOne(self::class,'id','parent');
    }
    public function parentSub(){
    	return $this->hasOne(self::class,'id','parent');
    }
    public function parentSubSub(){
    	return $this->hasOne(self::class,'id','parent');
    }
    public function parentSubSubSub(){
    	return $this->hasOne(self::class,'id','parent');
    }

    
    public function parentSubSubSubWithSubSub(){
    	return $this->hasOne(self::class,'id','parent')->with('parentSubSubWithSub');
    }
    public function parentSubSubWithSub(){
    	return $this->hasOne(self::class,'id','parent')->with('parentSubWithCat');
    }
    public function parentSubWithCat(){
    	return $this->hasOne(self::class,'id','parent')->with('parentCat');
    }

    
    public function subCategoriesWithSubSub(){
    	return $this->hasMany(Category::class,'parent','id')->with('subsubCategoriesWithSubSubSub');
    }
    public function subsubCategoriesWithSubSubSub(){
    	return $this->hasMany(Category::class,'parent','id')->with('subsubsubCategoriesWithSubSubSubSub');
    }
    public function subsubsubCategoriesWithSubSubSubSub(){
    	return $this->hasMany(Category::class,'parent','id')->with('subsubsubsubCategoriesWithSubSub');
    }
    public function subsubsubsubCategoriesWithSubSub(){
    	return $this->hasMany(Category::class,'parent','id');
    }


    public function subcategories(){
    	return $this->hasMany(Category::class,'parent','id');
    }
    public function subsubcategories(){
    	return $this->hasMany(Category::class,'parent','id');
    }
    public function subsubsubcategories(){
    	return $this->hasMany(Category::class,'parent','id');
    }
    public function subsubsubsubcategories(){
    	return $this->hasMany(Category::class,'parent','id');
    }
}
