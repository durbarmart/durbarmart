<?php

namespace App;

use App\Product;
use App\User;
use App\Seller;
use App\Shop;
// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Carbon\Carbon;
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation,SkipsOnFailure 
{
    private $user;

    public function __construct()
    {
        $this->user = User::select('id');
        $this->category = Category::select('id');
        $this->subcategory = SubCategory::select('id');
        $this->subsubcategory = SubSubCategory::select('id');
        $this->subsubsubcategory = SubSubSubCategory::select('id');
        $this->subsubsubsubcategory = SubSubSubSubCategory::select('id');
        $this->brand_id = Brand::select('id');
    }
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
    public function collection(Collection $row)
    {   
        set_time_limit(0);     
        $vendor_exists = 0;
// dd($row);
        foreach($row as $c => $d){
            $cat_id = '';
            $subcat_id = '';
            $subsubcat_id = '';
            $subsubsubcat_id = '';
            $subsubsubsubcat_id = '';
            $subsubsubsubsubcat_id = '';
            $images = [];
            $product = [
                'name' => '',
                'added_by' => '',
                'user_id' => '',
                'category_id' => '',
                'subcategory_id' => '',
                'subsubcategory_id' => '',
                'subsubsubcategory_id' => '',
                'subsubsubsubcategory_id' => '',
                'brand_id' => '',
                'unit' => '',
                'unit_price' => '',
                'video_provider' => '',
                'video_link' => '',
                'current_stock' => '',
                'meta_title' => '',
                'meta_description' => '',
                'description' => '',
                'extra_desc' => '',
                'slug' => '',
                'published' => '',
                'photos' => '',
                'thumbnail_img' => '',
                'featured_img' => '',
                'flash_deal_img' => '',
    
            ];
            $product_num = 0;
            foreach($d as $a => $b){
                if($a == 'title' && $b != ''){
                    $product['name'] = $b;
                    
                    $x = str_replace(')','',$b);

                    $y = str_replace('-',' ',$x);
                    
                    $z = (preg_replace('/[^A-Za-z0-9\-]/', ' ', $y));
                    
                    $w = preg_replace('!\s+!', ' ', $z);

                    $product_count = Product::where('name',trim(str_replace("'", "",$product['name'])))->count();
                    if($product_count > 0){
                        $product_num = $product_count + 1;
                    }else{
                        $product_num = 0;
                    }

                    if($product_num > 0){
                        $product['slug'] = str_replace(' ','-',strtolower(trim($w))).'-'.$product_num;
                    }else{
                        $product['slug'] = str_replace(' ','-',strtolower(trim($w)));
                    } 
                    // $product['slug'] = str_replace(' ','-',strtolower(trim($b)));
                }
                elseif($a == 'handle' && $b != ''){
                }
                elseif($a == 'vendor' && $b != ''){
                    if($b != 'admin'){
                        $product['added_by'] = 'seller';
                        $user = User::where('name',trim($b))->count();
                        if($user > 0){
                            $vendor_exists = 1;
                        }else{
                            $user = User::create([
                                'user_type' => 'seller',
                                'name' => trim($b),
                                'email' => null,
                                'email_verified_at' => Carbon::now()
                            ]);
                            $seller = Seller::create([
                                'user_id' => $user->id,
                                'verification_status' => 1,
                            ]);
                            $x = str_replace(')','',$b);

                            $y = str_replace('-',' ',$x);
                            
                            $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                            
                            $w = preg_replace('!\s+!', ' ', $z);
                            $shop = Shop::create([
                                'user_id' => $user->id,
                                'name' => trim($b),
                                'slug' => str_replace(' ','-',strtolower(trim($w)))
                            ]);
                            $vendor_exists = 1;
                        }
                        $user =User::where('name',trim($b))->first()->toArray();
                        $product['user_id'] = $user['id'];    
                    }else{
                        $product['added_by'] = 'admin';
                        $user = User::where('user_type','admin')->first();
                        $product['user_id'] = $user->id;
                    }
                                    
                }
                elseif($a == 'first' && $b != ''){
                    $category_id = Category::where('type','Category')->where('name',trim(str_replace("'", "", $b)))->count();
                    if(($category_id <= 0)){
                        $x = str_replace(')','',$b);

                        $y = str_replace('-',' ',$x);
                        
                        $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                        
                        $w = preg_replace('!\s+!', ' ', $z);
                        $category_id = Category::create([
                            'name' => trim(str_replace("'", "", $b)),
                            'type' => 'Category',
                            'slug' => str_replace(' ','-',strtolower(trim($w))),
                            'meta_title' => trim($b),
                            'meta_description' => trim($b)
                        ]);
                    }
                    // dd($category_id);
                    $category_id = Category::where('type','Category')->where('name',trim(str_replace("'", "", $b)))->first()->toArray();
                    $product['category_id'] = $category_id['id'];     
                    $product['main_id'] = $category_id['id'];     
                    $cat_id = $category_id['id'];    
                }
                elseif($a == 'second' && $b != ''){
                    $subcategory = Category::select('id')->where('type','SubCategory')->where('parent',$cat_id)->where('name',trim(str_replace("'", "", $b)))->count();
                    if(($subcategory <= 0)){
                        $x = str_replace(')','',$b);

                        $y = str_replace('-',' ',$x);
                        
                        $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                        
                        $w = preg_replace('!\s+!', ' ', $z);
                        $subcategory = Category::create([
                            'name' => trim(str_replace("'", "", $b)),
                            'parent' => $product['category_id'],
                            'type' => 'SubCategory',
                            'slug' => str_replace(' ','-',strtolower(trim(str_replace("'", "", $w)))),
                            'meta_title' => trim(str_replace("'", "", $b)),
                            'meta_description' => trim($b)
                        ]);
                    }
                    $subcategory = Category::select('id')->where('type','SubCategory')->where('parent',$cat_id)->where('name',trim(str_replace("'", "", $b)))->first();
                    $z = $subcategory->id;
                    $product['subcategory_id'] = $z;  
                    $product['main_id'] = $z;    
                    $subcat_id = $z;  
                   
                }
                elseif($a == 'third' && $b != ''){
                    $subsubcategory = Category::select('id')->where('type','SubSubCategory')->where('parent',$subcat_id)->where('name',trim(str_replace("'", "", $b)))->count();
                          
                    if(($subsubcategory == 0)){
                        $x = str_replace(')','',$b);

                        $y = str_replace('-',' ',$x);
                        
                        $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                        
                        $w = preg_replace('!\s+!', ' ', $z);
                        $subsubcategory = Category::create([
                            'name' => trim(str_replace("'", "", $b)),
                            'type' => 'SubSubCategory',
                            'parent' => $product['subcategory_id'],
                            'slug' => str_replace(' ','-',strtolower(trim(str_replace("'", "", $w)))),
                            'meta_title' => trim($b),
                            'meta_description' => trim($b)
                        ]);
                    }
                    // dd($subsubcategory);
                    $subsubcategory = Category::select('id')->where('type','SubSubCategory')->where('parent',$subcat_id)->where('name',trim(str_replace("'", "", $b)))->first()->toArray();
                    $product['subsubcategory_id'] = $subsubcategory['id'];
                              
                    $subsubcat_id = $subsubcategory['id'];
                    $product['main_id'] = $subsubcat_id;    
                
                }
                elseif($a == 'fourth' && $b != ''){
                    // dd('as');
                    $subsubsubcategory = Category::select('id')->where('type','SubSubSubCategory')->where('parent',$subsubcat_id)->where('name',trim(str_replace("'", "", $b)))->count();
                    if(($subsubsubcategory <= 0)){
                        $x = str_replace(')','',$b);

                        $y = str_replace('-',' ',$x);
                        
                        $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                        
                        $w = preg_replace('!\s+!', ' ', $z);
                        $subsubcategory = Category::create([
                            'name' => trim(str_replace("'", "", $b)),
                            'type' => 'SubSubSubCategory',
                            'parent' => $product['subsubcategory_id'],
                            'slug' => str_replace(' ','-',strtolower(trim(str_replace("'", "", $w)))),
                            'meta_title' => trim($b),
                            'meta_description' => trim($b)
                        ]);
                    }
                    $subsubsubcategory = Category::select('id')->where('type','SubSubSubCategory')->where('parent',$subsubcat_id)->where('name',trim(str_replace("'", "", $b)))->first()->toArray();
                    $product['subsubsubcategory_id'] = $subsubsubcategory['id'];  
                    $subsubsubcat_id = $subsubsubcategory['id'];  
                    $product['main_id'] = $subsubsubcat_id;    
                
                }
                elseif($a == 'fifth' && $b != ''){
                    $subsubsubsubcategory = Category::select('id')->where('type','SubSubSubSubCategory')->where('parent',$subsubsubcat_id)->where('name',trim(str_replace("'", "", $b)))->count();
                    if(($subsubsubsubcategory <= 0)){
                        $x = str_replace(')','',$b);

                        $y = str_replace('-',' ',$x);
                        
                        $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                        
                        $w = preg_replace('!\s+!', ' ', $z);
                        $subsubcategory = Category::create([
                            'name' => trim(str_replace("'", "", $b)),
                            'type' => 'SubSubSubSubCategory',
                            'parent' => $product['subsubsubcategory_id'],
                            'slug' => str_replace(' ','-',strtolower(trim(str_replace("'", "", $w)))),
                            'meta_title' => trim($b),
                            'meta_description' => trim($b)
                        ]);
                    }
                    $subsubsubsubcategory = Category::select('id')->where('type','SubSubSubSubCategory')->where('parent',$subsubsubcat_id)->where('name',trim(str_replace("'", "", $b)))->first()->toArray();
                    $product['subsubsubsubcategory_id'] = $subsubsubsubcategory['id'];
                    $subsubsubsubcat_id = $subsubsubsubcategory['id'];  
                         
                    
                    $product['main_id'] = $subsubsubsubcategory['id'];    
                    // $subsubcat_id = '';
                    // $subsubcat_id = '';   
                }
                elseif($a == 'sixth' && $b != ''){
                    $subsubsubsubsubcategory = Category::select('id')->where('type','SubSubSubSubSubCategory')->where('parent',$subsubsubsubcat_id)->where('name',trim(str_replace("'", "", $b)))->count();
                    
                    if(($subsubsubsubsubcategory <= 0)){
                        $x = str_replace(')','',$b);

                        $y = str_replace('-',' ',$x);
                        
                        $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                        
                        $w = preg_replace('!\s+!', ' ', $z);
                        $subsubcategory = Category::create([
                            'name' => trim(str_replace("'", "", $b)),
                            'type' => 'SubSubSubSubSubCategory',
                            'parent' => $product['subsubsubsubcategory_id'],
                            'slug' => str_replace(' ','-',strtolower(trim(str_replace("'", "", $w)))),
                            'meta_title' => trim($b),
                            'meta_description' => trim($b)
                        ]);
                    }
                    $subsubsubsubsubcategory = Category::select('id')->where('type','SubSubSubSubSubCategory')->where('parent',$subsubsubsubcat_id)->where('name',trim(str_replace("'", "", $b)))->first()->toArray();
                    $product['subsubsubsubsubcategory_id'] = $subsubsubsubsubcategory['id'];
                    $subsubsubsubsubcat_id = $subsubsubsubsubcategory['id'];  
                         
                    
                    $product['main_id'] = $subsubsubsubsubcategory['id'];    
                    // $subsubcat_id = '';
                    // $subsubcat_id = '';   
                }
                elseif($a == 'seventh' && $b != ''){
                    $subsubsubsubsubsubcategory = Category::select('id')->where('type','SubSubSubSubSubSubCategory')->where('parent',$subsubsubsubsubcat_id)->where('name',trim(str_replace("'", "", $b)))->count();
                    
                    if(($subsubsubsubsubsubcategory <= 0)){
                        $x = str_replace(')','',$b);

                        $y = str_replace('-',' ',$x);
                        
                        $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
                        
                        $w = preg_replace('!\s+!', ' ', $z);
                        $subsubcategory = Category::create([
                            'name' => trim(str_replace("'", "", $b)),
                            'type' => 'SubSubSubSubSubSubCategory',
                            'parent' => $product['subsubsubsubsubcategory_id'],
                            'slug' => str_replace(' ','-',strtolower(trim(str_replace("'", "", $w)))),
                            'meta_title' => trim($b),
                            'meta_description' => trim($b)
                        ]);
                    }
                    // dd(Category::select('id')->where('type','SubSubSubSubSubSubCategory')->where('parent',$subsubsubsubsubcat_id)->where('name',trim(str_replace("'", "", $b)))->first(),trim(str_replace("'", "", $b)),$subsubsubsubsubcat_id,$subsubsubsubsubsubcategory);
                    $subsubsubsubsubsubcategory = Category::select('id')->where('type','SubSubSubSubSubSubCategory')->where('parent',$subsubsubsubsubcat_id)->where('name',trim(str_replace("'", "", $b)))->first()->toArray();
                    $product['subsubsubsubsubsubcategory_id'] = $subsubsubsubsubsubcategory['id'];
                         
                    
                    $product['main_id'] = $subsubsubsubsubsubcategory['id'];    
                    // $subsubcat_id = '';
                    // $subsubcat_id = '';   
                }
                elseif($a == 'brand' && $b != ''){
                    // $product['brand_id'] = $b;
                    $brand_id = $this->brand_id->where('name',trim($b))->count();
                    if(!($brand_id > 0)){
                        $brand_id = Brand::create([
                            'name' => trim($b)
                        ]);
                    }
                    $brand_id = $this->brand_id->where('name',trim($b))->first()->toArray();
                    $product['brand_id'] = $brand_id['id'];
                }
                elseif($a == 'tags' && $b != ''){
                    $product['tags'] = $b;
                }
                elseif($a == 'variant_inventory_qty' && $b != ''){
                    $product['current_stock'] = $b;
                }
                elseif($a == 'variant_compare_at_price' && $b != ''){
                    // $product['discount'] = $product['unit_price'] - $b;
                    $product['unit_price'] = $b;
                }
                elseif($a == 'variant_price' && $b != ''){
                    // $product['unit_price'] = $b;
                    if(isset($product['unit_price']) && !empty($product['unit_price'])){
                        $product['discount'] = $product['unit_price'] - $b;
                        $product['discount_type'] = 'amount';
                    }else{
                        $product['unit_price'] = $b;
                    }
                }
                elseif($a == 'video_provider' && $b != ''){
                    $product['video_provider'] = $b;
                }
                elseif($a == 'video_link' && $b != ''){
                    $product['video_link'] = $b;
                }
                elseif($a == 'seo_title' && $b != ''){
                    $product['meta_title'] = $b;
                }
                elseif($a == 'seo_description' && $b != ''){
                    $product['meta_description'] = $b;
                }
                elseif($a == 'description' && $b != ''){
                    $product['description'] = $b;
                }
                elseif($a == 'body_html' && $b != ''){
                    $product['extra_desc'] = $b;
                }
                // elseif($a == 'added_by' && $b != ''){
                //     $product['added_by'] = $b;
                // }
                elseif($a == 'image_src' && $b != ''){
                    // dd($a);
                    $product['featured_img'] = $b;
                    $product['thumbnail_img'] = $b;
                    $product['flash_deal_img'] = $b; 
                    array_push($images,$b);            
                }
                elseif($a == 'image2' && $b != ''){
                    array_push($images,$b);
                }
                elseif($a == 'image3' && $b != ''){
                    array_push($images,$b);
                }
                elseif($a == 'image4' && $b != ''){
                    array_push($images,$b);
                }
                elseif($a == 'image5' && $b != ''){
                    array_push($images,$b);
                }
                elseif($a == 'image6' && $b != ''){
                    array_push($images,$b);
                }
                elseif($a == 'image7' && $b != ''){
                    array_push($images,$b);
                }
                elseif($a == 'image8' && $b != ''){
                    array_push($images,$b);
                }

                elseif($a == 'status'){
                    if($b == 'active'){
                        $product['published'] = 1;
                    }else{
                        $product['published'] = 0;
                    }
                    
                }
                // if(count($images) == 0){
                    // $images = [$product['featured_img']];
                    // dd($product['featured_img']);
                    // array_push($images,$product['featured_img']);
                // } 
                $product['photos'] = json_encode($images);     
                
            
                
                $product['  choice_options'] = json_encode(array());
                $product['colors'] =json_encode(array());
                // dd($product);
            }
            // dd($product);
            Product::create($product);
            $product = [];
            $images = [];
        }
        // dd($row,$product);
        // if($vendor_exists == 1){
           
        // }
        
        // return Product::create($product);
        return true;
        // dd($row);
        return new Product([
           'name'     => $row['name'],
           'added_by'    => Auth::user()->user_type == 'seller' ? 'seller' : 'admin',
           'user_id'    => Auth::user()->user_type == 'seller' ? Auth::user()->id : User::where('user_type', 'admin')->first()->id,
           'category_id'    => $row['category_id'],
           'subcategory_id'    => $row['subcategory_id'],
           'subsubcategory_id'    => $row['subsubcategory_id'],
           'brand_id'    => $row['brand_id'],
           'video_provider'    => $row['video_provider'],
           'video_link'    => $row['video_link'],
           'unit_price'    => $row['unit_price'],
           'purchase_price'    => $row['purchase_price'],
           'unit'    => $row['unit'],
           'current_stock' => $row['current_stock'],
           'meta_title' => $row['meta_title'],
           'meta_description' => $row['meta_description'],
           'colors' => json_encode(array()),
           'choice_options' => json_encode(array()),
           'variations' => json_encode(array()),
           'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $row['slug'])).'-'.str_random(5),
        ]);
    }

    public function rules(): array
    {
        return [
             // Can also use callback validation rules
             'unit_price' => function($attribute, $value, $onFailure) {
                  if (!is_numeric($value)) {
                       $onFailure('Unit price is not numeric');
                  }
              }
        ];
    }
}
