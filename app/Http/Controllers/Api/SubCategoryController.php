<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SubCategoryCollection;
use App\Http\Resources\SubSubCategoryCollection;
use App\Models\SubCategory;
use App\Models\SubSubCategory;

class SubCategoryController extends Controller
{
    public function index($id)
    {
        return new SubCategoryCollection(SubCategory::where('category_id', $id)->get());
    }
    public function subsubcat($id)
    {
        return new SubSubCategoryCollection(SubSubCategory::where('sub_category_id', $id)->get());
    }
}
