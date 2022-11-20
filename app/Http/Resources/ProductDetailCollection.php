<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Review;
use App\Models\Attribute;

class ProductDetailCollection extends ResourceCollection
{
    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function($data) {
                $photo=[];
                $placeholder_img='frontend/images/placeholder.jpg';
                if(!(isset($data->photos)) && empty($data->photos)){
                    array_push($photo,$placeholder_img);
                }else{
                    foreach(json_decode($data->photos) as $key=>$img){
                        if(file_exists($img)){
                            array_push($photo,$img);
                        }else{
                            array_push($photo,$placeholder_img);
                        }
                    }
                }
                return [
                    'id' => (integer) $data->id,
                    'name' => $data->name,
                    'variant_product' => $data->variant_product,
                    'added_by' => $data->added_by,
                    'user' => [
                        'name' => $data->user->name,
                        'email' => $data->user->email,
                        'avatar' =>file_exists($data->user->avatar) ? $data->user->avatar : $placeholder_img,
                        'avatar_original' =>file_exists($data->user->avatar_original) ? $data->user->avatar_original : $placeholder_img,
                        
                        'shop_name' => $data->added_by == 'admin' ? '' : $data->user->shop->name,
                        'shop_logo' => $data->added_by == 'admin' ? '' : (file_exists($data->user->shop->logo)?$data->user->shop->logo:$placeholder_img),
                        'shop_id' => $data->added_by == 'admin' ? '' :  (($data->user->shop)?strval($data->user->shop->id):'')
                    ],
                    'category' => [
                        'id' => $data->category_id,
                        'name' => $data->category->name,
                        'banner' => file_exists($data->category->banner) ? $data->category->banner : $placeholder_img,
                        'icon' => file_exists($data->category->icon) ? $data->category->icon : $placeholder_img,
                        'links' => [
                            'products' => route('api.products.category', $data->category_id),
                            'sub_categories' => route('subCategories.index', $data->category_id)
                        ]
                    ],
                    'sub_category' => [
                        'name' => $data->subCategory->name,
                        'links' => [
                            'products' => route('products.subCategory', $data->subcategory_id)
                        ]
                    ],
                    'brand' => [
                        'id' => $data->brand_id ?? 'N/A',
                        'name' => $data->brand->name ?? 'N/A',
                        'logo' => $data->brand->logo ?? $placeholder_img,
                        'links' => [
                            'products' => route('api.products.brand', $data->brand_id ?? '/')
                        ]
                    ],
                    'photos' => $photo,
                    'thumbnail_image' => file_exists($data->thumbnail_img) ? $data->thumbnail_img : $placeholder_img,
                    'featured_image' => file_exists($data->featured_img) ? $data->featured_img : $placeholder_img,
                    'flash_deal_image' => file_exists($data->flash_deal_img) ? $data->flash_deal_img : $placeholder_img,
                    'tags' => explode(',', $data->tags),
                    'unit_price' => $data->unit_price,
                    'home_discounted_price'=>home_discounted_base_price($data->id),
                    'price_lower' => (double) explode('-', homeDiscountedPrice($data->id))[0],
                    'price_higher' => (double) explode('-', homeDiscountedPrice($data->id))[1],
                    'choice_options' => $this->convertToChoiceOptions(json_decode($data->choice_options)),
                    'colors' => json_decode($data->colors),
                    'todays_deal' => (integer) $data->todays_deal,
                    'featured' => (integer) $data->featured,
                    'current_stock' => (integer) $data->current_stock,
                    'unit' => $data->unit,
                    'discount' => (double) $data->discount,
                    'discount_type' => $data->discount_type,
                    'tax' => (double) $data->tax,
                    'tax_type' => $data->tax_type,
                    'warranty' => $data->warranty,
                    'warranty_time' => $data->warranty_time,
                    'shipping_type' => $data->shipping_type,
                    'shipping_cost' => (double) $data->shipping_cost,
                    'number_of_sales' => (integer) $data->num_of_sale,
                    'reviews' => Review::where(['product_id' => $data->id])->get(),
                    'rating' => (double) $data->rating,
                    'rating_count' => (integer) Review::where(['product_id' => $data->id])->count(),
                    'description' => $data->description,
                    'links' => [
                        'reviews' => route('api.reviews.index', $data->id),
                        'related' => route('products.related', $data->id)
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }

    protected function convertToChoiceOptions($data){
        $result = array();
        if(isset($data) && !empty($data)){
            foreach ($data as $key => $choice) {
                $item['name'] = $choice->attribute_id;
                $item['title'] = Attribute::find($choice->attribute_id)->name;
                $item['options'] = $choice->values;
                array_push($result, $item);
            }

        }
        return $result;
    }
}
