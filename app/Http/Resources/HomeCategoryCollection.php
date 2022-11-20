<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HomeCategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $placeholder_img='frontend/images/placeholder.jpg';
                return [
                    'name' => $data->category->name,
                    'banner' => file_exists($data->category->banner) ? $data->category->banner : $placeholder_img,
                    'icon' => file_exists($data->category->icon) ? $data->category->icon : $placeholder_img,
                    'links' => [
                        'products' => route('api.products.category', $data->category->id),
                        'sub_categories' => route('subCategories.index', $data->category->id)
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
