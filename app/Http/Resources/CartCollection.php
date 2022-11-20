<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $placeholder_img='frontend/images/placeholder.jpg';

                return [
                    'id' => $data->id,
                    'product' => [
                        'name' => $data->product->name,
                        'image' =>file_exists($data->product->thumbnail_img) ? $data->product->thumbnail_img : $placeholder_img,
                        'available_stock' => $data->product->current_stock,
                    ],
                    'variation' => $data->variation,
                    'price' => (double) $data->price,
                    'tax' => (double) $data->tax,
                    'shipping_cost' => (double) $data->shipping_cost,
                    'quantity' => (integer) $data->quantity,
                    'product_id' => $data->product->id,
                    'date' => $data->created_at->diffForHumans(),
                    
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
