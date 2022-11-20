<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Exports\ExportCategory;
use App\HomeCategory;
use App\Product;
use App\Language;
use App\Models\Brand;
use Exception;
use Illuminate\Support\Facades\Cache;

use Excel;

class CategoryController extends Controller
{
    public function catTree(){
        $categories_arranged = [];
        $categories = Category::where('type','Category')->with('subCategoriesWithSubSub')->get()->toArray();
        // $categories2[] = $categories[0];
        foreach($categories as $a => $b){
            $item = $b['name']; //bags and travel
            if(isset($b['sub_categories_with_sub_sub']) && !empty($b['sub_categories_with_sub_sub'])){
                foreach($b['sub_categories_with_sub_sub'] as $c => $d){
                    $item = $b['name']; //bags and travel
                    $item .= '/'.$d['name']; //bags and travel/travel
                    $temp_1 = $item;
                    if(isset($d['subsub_categories_with_sub_sub_sub']) && !empty($d['subsub_categories_with_sub_sub_sub'])){
                        foreach($d['subsub_categories_with_sub_sub_sub'] as $e => $f){
                            $item .= '/'.$f['name'];//bags and travel/travel/laptop bags
                            $temp_2 = $item;
                            if(isset($f['subsubsub_categories_with_sub_sub_sub_sub']) && !empty($f['subsubsub_categories_with_sub_sub_sub_sub'])){
                                foreach($f['subsubsub_categories_with_sub_sub_sub_sub'] as $g => $h){
                                    $item .= '/'.$h['name'];//bags and travel/travel/laptop bags/briefcase
                                    $temp_3 = $item;
                                    if(isset($h['subsubsubsub_categories_with_sub_sub']) && !empty($h['subsubsubsub_categories_with_sub_sub'])){
                                        foreach($h['subsubsubsub_categories_with_sub_sub'] as $i => $j){
                                            $item .= '/'.$h['name'];
                                        }
                                    }else{
                                        $categories_arranged[] = [
                                            'category' => $item,
                                            'category url' => $h['slug'],
                                            'category seo title' => $h['meta_title'],
                                            'category seo description' => $h['meta_description']
                                        ];
                                        $item = $temp_2;
                                    }
                                }
                            }else{
                                $categories_arranged[] = [
                                    'category' => $item,
                                    'category url' => $f['slug'],
                                    'category seo title' => $f['meta_title'],
                                    'category seo description' => $f['meta_description']
                                ];
                                $item = $temp_1;

                            }
                        }
                    }else{                        
                        $categories_arranged[] = [
                            'category' => $item,
                            'category url' => $d['slug'],
                            'category seo title' => $d['meta_title'],
                            'category seo description' => $d['meta_description']
                        ];
                        $item = $item;
                    }
                }
            }else{
                $categories_arranged[] = [
                    'category' => $item,
                    'category url' => $b['slug'],
                    'category seo title' => $b['meta_title'],
                    'category seo description' => $b['meta_description']
                ];
                $item = $item;
            }
            // $categories_arranged[] = [
            //     'category' => $item,
            //     'slug' => $b['slug']
            // ];
            $item = '';
        }
        // dd($categories[0]);
        echo '<pre>';
        print_r($categories_arranged);
        echo '<pre>';
        // dd($categories);
    }
    public function exportCategory(){
        return Excel::download(new ExportCategory,'category.xlsx');
    }
    public function bulkDelete(Request $request){
         // dd($request->all());
         $ids = $request->ids;
         // dd($ids);
         $exploded_ids = explode(",", $ids);
         // dd($exploded_ids);
         foreach ($exploded_ids as $dataId) {
             $product = Category::findOrFail($dataId);
             if (!Category::destroy($dataId)) {
                return response()->json(['error' => 'Something went wrong']);
             }
         }
         $redirectTo = route('categories.allCats');
 
         return response()->json(['success' => "Sellers Deleted successfully", 'redirectTo' => $redirectTo]);
    }
    public function all(Request $request){
        // dd(new Excel);
        $sort_search =null;
        $items_per_page = $_GET['items_per_page']??100;

        $categories = Category::orderBy('created_at', 'desc');
        if ($request->search != null) {
            $products = $categories
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        // $categories = Cache::remember('categories','3600',function(){
        //     return  Category::orderBy('created_at', 'desc')->get();
        // }); 
        // dd(Category::orderBy('created_at', 'desc')->first());
        $categories = $categories->paginate($items_per_page);
        // $categories = $categories->paginate(100);
        return view('categories.all', compact('categories','sort_search'));
    }
    public function truncate($table){
        try{
            Category::query()->truncate();
            // truncate($table);
            flash(__('Category Emptied'))->success();
            return back();
        }catch(Exception $e){
            dd($e);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $categories = Category::where('type','Category')->orWhere('type','BrandCategory')->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(100);
        return view('categories.index', compact('categories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $category = new Category;
        $category->name = $request->name;
        $category->type = 'Category';
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        if($request->brand_category == 'on'){
            $category->parent = $request->linked_category;
            $category->brand_id = $request->linked_brand;
            $category->type = 'BrandCategory';
        }

        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        // $data = openJSONFile('en');
        // $data[$category->name] = $category->name;
        // saveJSONFile('en', $data);

        if($request->hasFile('banner')){
            $category->banner = $request->file('banner')->store('uploads/categories/banner');
        }
        if($request->hasFile('icon')){
            $category->icon = $request->file('icon')->store('uploads/categories/icon');
        }

        $category->digital = $request->digital;
        if($category->save()){
            flash(__('Category has been inserted successfully'))->success();
            return redirect()->route('categories.index');
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
        $category = Category::findOrFail(decrypt($id));
        return view('categories.edit', compact('category'));
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
        $category = Category::findOrFail($id);

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$category->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // }

        $category->name = $request->name;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        
        if($request->brand_category == 'on'){
            $category->parent = $request->linked_category;
            $category->brand_id = $request->linked_brand;
            $category->type = 'BrandCategory';
        }else{
            $category->type = 'Category';

        }
        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        if($request->hasFile('banner')){
            $category->banner = $request->file('banner')->store('uploads/categories/banner');
        }
        if($request->hasFile('icon')){
            $category->icon = $request->file('icon')->store('uploads/categories/icon');
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->digital = $request->digital;
        if($category->save()){
            flash(__('Category has been updated successfully'))->success();
            return redirect()->route('categories.index');
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
        $category = Category::findOrFail($id);
        foreach ($category->subcategories as $key => $subcategory) {
            foreach ($subcategory->subsubcategories as $key => $subsubcategory) {
                $subsubcategory->delete();
            }
            $subcategory->delete();
        }

        Product::where('category_id', $category->id)->delete();
        HomeCategory::where('category_id', $category->id)->delete();

        if(Category::destroy($id)){
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$category->name]);
                saveJSONFile($language->code, $data);
            }

            if($category->banner != null){
                //($category->banner);
            }
            if($category->icon != null){
                //unlink($category->icon);
            }
            flash(__('Category has been deleted successfully'))->success();
            return redirect()->route('categories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        if($category->save()){
            return 1;
        }
        return 0;
    }

    public function updateComissionRate(Request $request){
        foreach($request->arr as $value){
            // $category = Category::find($value['id']);
            // if($category->commision_rate != $value['commission_rate']){
            //     $category->commision_rate = $value['commission_rate'];
            //     $category->save();
            // }
            Category::where('id', $value['id'])->update(['commision_rate'=>$value['commission_rate']]);
        }
        flash(__('Commission updated'))->success();
        return back();
    }
}
