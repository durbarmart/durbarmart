<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\SubSubCategory;
use App\Brand;
use App\Product;
use App\Language;

class SubSubSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $subsubcategories = Category::where('type','SubSubSubCategory')->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $subsubcategories = $subsubcategories->where('name', 'like', '%'.$sort_search.'%');
        }
        $subsubsubcategories = $subsubcategories->paginate(100);
        return view('subsubsubcategories.index', compact('subsubsubcategories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('type','Category')->get();
        $brands = Brand::all();
        return view('subsubsubcategories.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subsubcategory = new Category;
        $subsubcategory->name = $request->name;
        $subsubcategory->type = 'SubSubSubCategory';
        $subsubcategory->parent = $request->sub_sub_category_id;
        //$subsubcategory->attributes = json_encode($request->choice_attributes);
        //$subsubcategory->brands = json_encode($request->brands);
        $subsubcategory->meta_title = $request->meta_title;
        $subsubcategory->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        // $data = openJSONFile('en');
        // $data[$subsubcategory->name] = $subsubcategory->name;
        // saveJSONFile('en', $data);

        if($subsubcategory->save()){
            flash(__('SubSubSubCategory has been inserted successfully'))->success();
            return redirect()->route('subsubsubcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subsubcategory = Category::where('id',decrypt($id))->with('parentSubSubWithSub')->first();
        $categories = Category::where('type','Category')->get();
        $brands = Brand::all();

        return view('subsubsubcategories.edit', compact('subsubcategory', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subsubcategory = Category::findOrFail($id);

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$subsubcategory->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // }

        $subsubcategory->name = $request->name;
        $subsubcategory->parent = $request->sub_sub_category_id;
        //$subsubcategory->attributes = json_encode($request->choice_attributes);
        //$subsubcategory->brands = json_encode($request->brands);
        $subsubcategory->meta_title = $request->meta_title;
        $subsubcategory->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        if($subsubcategory->save()){
            flash(__('SubSubCategory has been updated successfully'))->success();
            return redirect()->route('subsubsubcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subsubcategory = Category::findOrFail($id);
        Product::where('subsubsubcategory_id', $subsubcategory->id)->delete();
        if(Category::destroy($id)){
            // foreach (Language::all() as $key => $language) {
            //     $data = openJSONFile($language->code);
            //     unset($data[$subsubcategory->name]);
            //     saveJSONFile($language->code, $data);
            // }
            flash(__('SubSubSubCategory has been deleted successfully'))->success();
            return redirect()->route('subsubsubcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function get_subsubsubcategories_by_subsubcategory(Request $request)
    {
        $subsubsubcategories = Category::where('parent', $request->sub_sub_category_id)->get();
        return $subsubsubcategories;
    }

    // public function get_brands_by_subsubcategory(Request $request)
    // {
    //     $brand_ids = json_decode(SubSubCategory::findOrFail($request->subsubcategory_id)->brands);
    //     $brands = array();
    //     foreach ($brand_ids as $key => $brand_id) {
    //         array_push($brands, Brand::findOrFail($brand_id));
    //     }
    //     return $brands;
    // }

    // public function get_attributes_by_subsubcategory(Request $request)
    // {
    //     $attribute_ids = json_decode(SubSubCategory::findOrFail($request->subsubcategory_id)->attributes);
    //     $attributes = array();
    //     foreach ($attribute_ids as $key => $attribute_id) {
    //         if(\App\Attribute::find($attribute_id) != null){
    //             array_push($attributes, \App\Attribute::findOrFail($attribute_id));
    //         }
    //     }
    //     return $attributes;
    // }
}
