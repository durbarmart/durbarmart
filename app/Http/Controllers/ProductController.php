<?php

namespace App\Http\Controllers;

use App\Category;
use App\Language;
use App\Product;
use App\ProductStock;
use App\User;
use Auth;
use Illuminate\Http\Request;

use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $guarded = array();

    public function admin_products(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();


        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;
        $items_per_page = $_GET['items_per_page']??100;

        $products = Product::where('added_by', 'admin');

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate($items_per_page);

        return view('products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDiscount(Request $request){
        $user = User::where('id',$request->seller)->count();
        if($user <1 ){
            flash(__('Seller Doesnot Exist'))->error();
            return back();
        }
        try{
            Product::where('user_id',$request->seller)->update([
            'discount' => $request->discount,
            'discount_type' => $request->discount_type
            ]);
            flash(__('Discounts Updated'))->success();
            return back();

        }  
        catch(\Exception $e)
        {
            $bug = $e->getMessage();
            flash(__($bug))->error();
            return back();
        }
        // dd($request->all());
    }

    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $status=3;
        $vendor_id=null;
        $items_per_page = $_GET['items_per_page']??100;
        
        $products = Product::where('added_by', 'seller');
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->seller != null) {
            
            $products = $products
                ->where('user_id', $request->seller);
            $vendor_id=$request->seller;
            
        }
        if ($request->status != null) {
            
            $products = $products
                ->where('published', $request->status);
            $status=$request->status;
            
        }
        // $limit = 1000;
        $products = $products->orderBy('created_at', 'desc')->paginate($items_per_page);
        // $products = $products->orderBy('created_at', 'desc')->get();
        $type = 'Seller';

        return view('products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search','status','vendor_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('type','Category')->get();
        return view('products.create', compact('categories'));
    }
    
    public function slugDuplication(){
        $products = Category::get()->toArray();

        dd($products);
    }
    public function test13123(){
        // ini_set('memory_limit', '-1');
        $products = Product::get()->toArray();
        foreach($products as $a => $b){
            $photo = json_decode($b['photos']);
            if(!empty($photo)){
                $photos = $photo[0];
                $products = Product::where('id',$b['id'])->update([
                    // 'photos' => json_encode($photos),
                    'thumbnail_img' => $photos,
                    'featured_img' => $photos,
                    'flash_deal_img' => $photos,
                ]);

            }
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();

        $product = new Product;
        $product->name = $request->name;

        if(isset($request->vendor_id) && $request->vendor_id == 'in-house'){
            $added_by = 'admin';
        }else{
            $added_by = 'seller';
        }
        $product->added_by = $added_by;

        if (Auth::user()->user_type == 'seller') {
            $product->user_id = Auth::user()->id;
        } else {
            // $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
            if(isset($request->vendor_id) && $request->vendor_id == 'in-house'){
                $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
            }else{  
                $product->user_id = $request->vendor_id;
                // $added_by = 'seller';
            }
        }
        $main_id = 0;
        if(isset($request->subsubcategory_id) && !empty($request->subsubcategory_id)){
            $main_id = $request->subsubcategory_id;
        }elseif(isset($request->subcategory_id) && !empty($request->subcategory_id)){
            $main_id = $request->subcategory_id;
        }else{
            $main_id = $request->category_id;
        }
        $product->main_id = $main_id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            } else {
                $product->refundable = 0;
            }
        }

        $product->made_in_nepal = $request->made_in_nepal != null ? 1 : 0;
        $product->warranty = $request->warranty != null ? 1 : 0;
        $product->warranty_time = $request->warranty_time;

        $photos = array();
        // $thumb=array();
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                // if($key == 0){
                //     $thumbnail_path = $photo->store('uploads/products/thumbnail');
                //     Image::make(public_path($thumbnail_path))->resize(100,100)->save();
                // }
                Image::make(public_path($path))->resize(750,750)->save();


                array_push($photos, $path);
                // array_push($thumb, $thumbnail_path);

                //ImageOptimizer::optimize(base_path('public/').$path);
            }
            $product->photos = json_encode($photos);
            // $product->thumbnail_img = json_encode($thumb);
            // $product->thumbnail_img = $thumb;
            // dd(json_decode($product->photos));


        }

        // if($request->hasFile('thumbnail_img')){
        //     $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
        //     // ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        // }

        if($request->hasFile('featured_img')){
            $product->featured_img = $request->featured_img->store('uploads/products/featured');
            $product->thumbnail_img = $request->featured_img->store('uploads/products/thumbnail');
            $product->meta_img = $request->featured_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->featured_img);
        }

        if ($request->hasFile('flash_deal_img')) {
            $product->flash_deal_img = $request->flash_deal_img->store('uploads/products/flash_deal');
            //ImageOptimizer::optimize(base_path('public/').$product->flash_deal_img);
        }

        $product->unit = $request->unit;
        $product->tags = implode('|', $request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->shipping_type = $request->shipping_type;
        if ($request->has('shipping_type')) {
            if ($request->shipping_type == 'free') {
                $product->shipping_cost = 0;
            } elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            }
        }
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        if ($request->hasFile('meta_img')) {
            $product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        }

        if ($request->hasFile('pdf')) {
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . str_random(5);

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = json_encode($request->colors);
        } else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        //$variations = array();

        $product->save();

        //combinations start
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $key => $item) {
                    if ($key > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                // $item = array();
                // $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                // $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                // $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                // $variations[$str] = $item;

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }

                $product_stock->variant = $str;
                $product_stock->price = $request['price_' . str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_' . str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_' . str_replace('.', '_', $str)];
                $product_stock->save();
            }
        }
        //combinations end

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     $data[$product->name] = $product->name;
        //     saveJSONFile($language->code, $data);
        // }

        $product->save();

        flash(__('Product has been inserted successfully'))->success();
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            // return redirect()->route('products.admin');
            if(isset($request->vendor_id) && $request->vendor_id == 'in-house'){
                return redirect()->route('products.admin');
            }else{
                return redirect()->route('products.seller');
            }
        } else {
            if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
                $seller = Auth::user()->seller;
                $seller->remaining_uploads -= 1;
                $seller->save();
            }
            return redirect()->route('seller.products');
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
    public function admin_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        $tags = json_decode($product->tags);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        $tags = json_decode($product->tags);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories', 'tags'));
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
        // dd($request->all());
        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $main_id = 0;
        if(isset($request->subsubcategory_id) && !empty($request->subsubcategory_id)){
            $main_id = $request->subsubcategory_id;
        }elseif(isset($request->subcategory_id) && !empty($request->subcategory_id)){
            $main_id = $request->subcategory_id;
        }else{
            $main_id = $request->category_id;
        }
        $product->main_id = $main_id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock1;
        $product->barcode = $request->barcode;
        $product->slug = $request->slug;

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            } else {
                $product->refundable = 0;
            }
        }

        if ($request->has('previous_photos')) {
            $photos = $request->previous_photos;
            // $thumb = $request->previous_thumbnail_img;
            // dd($request->all());
        }
        else{
            $photos = array();
            // $thumb=array();
        }
        // dd($thumb);

        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                // $thumbnail_path = $photo->store('uploads/products/thumbnail');

                Image::make(public_path($path))->resize(750,750)->save();
                // Image::make(public_path($thumbnail_path))->resize(100,100)->save();


                array_push($photos, $path);
                // array_push($thumb, $thumbnail_path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
        }
        $product->photos = json_encode($photos);
        $product->made_in_nepal = $request->made_in_nepal != null ? 1 : 0;
        $product->warranty = $request->warranty != null ? 1 : 0;
        $product->warranty_time = $request->warranty_time;
        // $product->thumbnail_img = json_encode($thumb);

        // $product->thumbnail_img = $request->previous_thumbnail_img;
        // if($request->hasFile('thumbnail_img')){
        //     $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
        //     //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        // }

        $product->featured_img = $request->previous_featured_img;
        

        if ($request->hasFile('featured_img')) {
            $product->featured_img = $request->featured_img->store('uploads/products/featured');
            $product->thumbnail_img = $request->featured_img->store('uploads/products/thumbnail');
            $product->meta_img = $request->featured_img->store('uploads/products/meta');
            
            // $product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->featured_img);
            // dd($product->meta_img);
        }

        $product->flash_deal_img = $request->previous_flash_deal_img;
        if ($request->hasFile('flash_deal_img')) {
            $product->flash_deal_img = $request->flash_deal_img->store('uploads/products/flash_deal');
            //ImageOptimizer::optimize(base_path('public/').$product->flash_deal_img);
        }

        $product->unit = $request->unit;
        $product->tags = implode('|', $request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->shipping_type = $request->shipping_type;
        if ($request->has('shipping_type')) {
            if ($request->shipping_type == 'free') {
                $product->shipping_cost = 0;
            } elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            }
        }
        $product->discount_type = $request->discount_type;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        // $product->meta_img = $request->previous_featured_img;
        // if ($request->hasFile('meta_img')) {
        //     $product->meta_img = $request->meta_img->store('uploads/products/meta');
        //     //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        // }

        if ($request->hasFile('pdf')) {
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        if($request->slug == ''){
            $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . substr($product->slug, -5);

        }

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = json_encode($request->colors);
        } else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if ($product->attributes != json_encode($request->choice_attributes)) {
            foreach ($product->stocks as $key => $stock) {
                $stock->delete();
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$product->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // }

        //combinations start
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $key => $item) {
                    if ($key > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }

                $product_stock->variant = $str;
                $product_stock->price = $request['price_' . str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_' . str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_' . str_replace('.', '_', $str)];

                $product_stock->save();
            }
        }

        if(isset($request->vendor_id) && $request->vendor_id == 'in-house'){
            $added_by = 'admin';
        }else{
            $added_by = 'seller';
        }

        $product->added_by = $added_by;

        if (Auth::user()->user_type == 'seller') {
            $product->user_id = Auth::user()->id;
        } else {
            if(isset($request->vendor_id) && $request->vendor_id == 'in-house'){
                $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
            }else{
                $product->user_id = $request->vendor_id;
                // $added_by = 'seller';
            }
           
        }

        $product->save();
        flash(__('Product has been updated successfully'))->success();

        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            // if($product->added_by== 'admin'){
            //     return redirect()->route('products.admin');
            // }else{
            //     return redirect()->route('products.seller');
            // }
            if(isset($request->vendor_id) && $request->vendor_id == 'in-house'){
                return redirect()->route('products.admin');
            }else{
                return redirect()->route('products.seller');
            }
        } else {
            return redirect()->route('seller.products');
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
        $product = Product::findOrFail($id);
        if (Product::destroy($id)) {
            // foreach (Language::all() as $key => $language) {
            //     $data = openJSONFile($language->code);
            //     unset($data[$product->name]);
            //     saveJSONFile($language->code, $data);
            // }
            flash(__('Product has been deleted successfully'))->success();
            if (Auth::user()->user_type == 'admin') {
                return redirect()->route('products.admin');
            } else {
                return redirect()->route('seller.products');
            }
        } else {
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function bulkDelete(Request $request)
    {
        // dd($request->all());
        $ids = $request->ids;
        // dd($ids);
        $exploded_ids = explode(",", $ids);
        // dd($exploded_ids);
        foreach ($exploded_ids as $dataId) {
            $product = Product::findOrFail($dataId);
            if (Product::destroy($dataId)) {
                $this->__deleteProduct($product, $dataId);
            } else {
                return response()->json(['error' => 'Something went wrong']);
            }
        }
        if (Auth::user()->user_type == 'admin') {
            $redirectTo = route('products.admin');
        } else {
            $redirectTo = route('seller.products');
        }

        return response()->json(['success' => "Product Deleted successfully", 'redirectTo' => $redirectTo]);
    }

    private function __deleteProduct($product, $id)
    {
        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$product->name]);
        //     saveJSONFile($language->code, $data);
        // }
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
        $product = Product::find($id);
        $product_new = $product->replicate();
        $product_new->slug = substr($product_new->slug, 0, -5) . str_random(5);

        if ($product_new->save()) {
            flash(__('Product has been duplicated successfully'))->success();
            if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
                return redirect()->route('products.admin');
            } else {
                return redirect()->route('seller.products');
            }
        } else {
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function get_products_by_subsubcategory(Request $request)
    {
        $products = Product::where('subsubcategory_id', $request->subsubcategory_id)->get();
        return $products;
    }

    public function get_productids_by_category(Request $request)
    {
        $products = Product::whereIn('category_id', $request->category_ids)->get(['id']);
        return $products;
    }

    public function get_productids_by_seller(Request $request)
    {
        $products = Product::whereIn('user_id', $request->seller_ids)->get(['id']);
        return $products;
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && \App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            $seller = $product->user->seller;
            if ($seller->invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0) {
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function updatePriceOrStock(Request $request)
    {
        $product = Product::where('id', $request->pk);
        if ($product->exists()) {
            $product = $product->where('id', $request->pk)->first();
        } else {
            return response()->json(['success' => false, 'message' => 'Product doesn\'t exist']);
        }
        if ($request->name == 'qty') {
            $product->update(['current_stock' => $request->value]);
            return response()->json(['success' => true, 'message' => "Product stock updated successfully"]);
        }

        if ($request->name == 'price') {
            $product->update(['unit_price' => $request->value]);
            return response()->json(['success' => true, 'message' => "Product price updated successfully"]);
        }
    }

    public function get_products_by_category(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->get();
        return $products;
    }

}
