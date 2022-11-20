<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Product;

class PurchaseHistoryDetailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
        $product = Product::where('id',$data->product_id)->first();
                return [
                    'product' => (isset($product))?$product->name:'Empty',
                    'variation' => $data->variation,
                    'price' => $data->price,
                    'tax' => $data->tax,
                    'shipping_cost' => $data->shipping_cost,
                    'quantity' => $data->quantity,
                    'payment_status' => $data->payment_status,
                    'delivery_status' => $data->delivery_status
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
