<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use SebastianBergmann\Environment\Console;

class ProductCollection extends ResourceCollection
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
                    // array_push($photo,$img);
                // return ($data->photos);
                    $items = json_decode($data->photos);
                    if(count(array($items)) > 0){
                        foreach($items as $key=>$img){
                            if(file_exists($img)){
                                array_push($photo,$img);
                            }else{
                                array_push($photo,$placeholder_img);
                            }
                        }
                    }
                }
                return [
                    'id' => (integer) $data->id,
                    'name' => $data->name,
                    'photos' => $photo,
                    'thumbnail_image' => (filter_var($data->thumbnail_img, FILTER_VALIDATE_URL))?$data->thumbnail_img:(file_exists($data->thumbnail_img) ? $data->thumbnail_img : $placeholder_img),
                    'featured_image' => (filter_var($data->featured_img, FILTER_VALIDATE_URL))?$data->featured_img:(file_exists($data->featured_img) ? $data->featured_img : $placeholder_img),
                    'flash_deal_image' => (filter_var($data->flash_deal_img, FILTER_VALIDATE_URL))?$data->flash_deal_img:(file_exists($data->flash_deal_img) ? $data->flash_deal_img : $placeholder_img),
                    'unit_price' =>number_format($data->unit_price),
                    'base_price' => (double) homeBasePrice($data->id),
                    'base_discounted_price' => (double) number_format(homeDiscountedBasePrice($data->id)),
                    'todays_deal' => (integer) $data->todays_deal,
                    'featured' =>(integer) $data->featured,
                    'unit' => $data->unit,
                    'discount' => (double) $data->discount,
                    'discount_type' => $data->discount_type,
                    'rating' => (double) $data->rating,
                    'sales' => (integer) $data->num_of_sale,
                    'links' => [
                        'details' => route('products.show', $data->id),
                        'reviews' => route('api.reviews.index', $data->id),
                        'related' => route('products.related', $data->id),
                        'top_from_seller' => route('products.topFromSeller', $data->id)
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
}
