<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $placeholder_img='frontend/images/placeholder.jpg';
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'banner' =>file_exists($data->banner) ? $data->banner : $placeholder_img,
                    'icon' => file_exists($data->icon) ? $data->icon : $placeholder_img,
                    // 'brands' => brandsOfCategory($data->id),
                    'links' => [
                        'products' => route('api.products.category', $data->id),
                        'sub_categories' => route('subCategories.index', $data->id)
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
