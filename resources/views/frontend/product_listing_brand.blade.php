@extends('frontend.layouts.app')

{{-- @php
    dd($__data);
@endphp --}}

@php
    $meta_title = '';
    $meta_description = '';
@endphp
@if(isset($subsubcategory_id) && !empty($subsubcategory_id))
    @php
        $brand_seo_subsubcat = \App\BrandSeo::where([
            'brand_id'=> $brand_id,
            'type' => 'subsubcategory',
            'type_id' => $subsubcategory_id
        ])->count();
        if($brand_seo_subsubcat > 0){
            $brand_seo_subsubcat = \App\BrandSeo::where([
                'brand_id'=> $brand_id,
                'type' => 'subsubcategory',
                'type_id' => $subsubcategory_id
            ])->first();
            $meta_title = $brand_seo_subsubcat->seo_title;
            $meta_description = $brand_seo_subsubcat->seo_description;
        }   
        // $meta_title = \App\SubSubCategory::find($subsubcategory_id)->meta_title;
        // $meta_description = \App\SubSubCategory::find($subsubcategory_id)->meta_description;
    @endphp
@elseif (isset($subcategory_id) && !empty($subcategory_id))
    @php
        $brand_seo_subcat = \App\BrandSeo::where([
            'brand_id'=> $brand_id,
            'type' => 'subcategory',
            'type_id' => $subcategory_id
        ])->count();
        if($brand_seo_subcat > 0){
            $brand_seo_subcat = \App\BrandSeo::where([
                'brand_id'=> $brand_id,
                'type' => 'subcategory',
                'type_id' => $subcategory_id
            ])->first();
            $meta_title = $brand_seo_subcat->seo_title;
            $meta_description = $brand_seo_subcat->seo_description;
        }
        // $meta_title = \App\SubCategory::find($subcategory_id)->meta_title;
        // $meta_description = \App\SubCategory::find($subcategory_id)->meta_description;
    @endphp
@elseif (isset($category_id) && !empty($category_id))
    @php
        $brand_seo_cat = \App\BrandSeo::where([
            'brand_id'=> $brand_id,
            'type' => 'category',
            'type_id' => $category_id
        ])->count();
        if($brand_seo_cat > 0){
            $brand_seo_cat = \App\BrandSeo::where([
                'brand_id'=> $brand_id,
                'type' => 'category',
                'type_id' => $category_id
            ])->first();
            $meta_title = $brand_seo_cat->seo_title;
            $meta_description = $brand_seo_cat->seo_description;
        }
    @endphp
@elseif (isset($brand_id) && !empty($brand_id))
    @php
        $meta_title = \App\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Brand::find($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title = env('APP_NAME');
        $meta_description = \App\SeoSetting::first()->description;
    @endphp
@endif
{{-- @php
    dd($meta_title,$meta_description,\App\Category::find($category_id)->first());
@endphp --}}
@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ $meta_title }}">
<meta itemprop="description" content="{{ $meta_description }}">

<!-- Twitter Card data -->
<meta name="twitter:title" content="{{ $meta_title }}">
<meta name="twitter:description" content="{{ $meta_description }}">

<!-- Open Graph data -->
<meta property="og:title" content="{{ $meta_title }}" />
<meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')

<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="breadcrumb">
                    <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                    <li><a href="{{ route('products') }}">{{__('All Categories')}}</a></li>
                    
                
                </ul>
            </div>
        </div>
    </div>
</div>


<section class="gry-bg py-4">
    <div class="container sm-px-0">
        <form class="" id="search-form" action="{{ route('search') }}" method="GET">
            <div class="row">
                <div class="col-xl-3 side-filter d-xl-block">
                    <div class="filter-overlay filter-close"></div>
                    <div class="filter-wrapper c-scrollbar">
                        <div class="filter-title d-flex d-xl-none justify-content-between pb-3 align-items-center">
                            <h3 class="h6">Filters</h3>
                            <button type="button" class="close filter-close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                {{__('Categories')}}
                            </div>
                            <div class="box-content">
                                <div class="category-filter">
                                    <ul>
                                        @if(!isset($category_id) && !isset($category_id) && !isset($subcategory_id) && !isset($subsubcategory_id))
                                            @foreach(\App\Category::all() as $category)
                                                @if ($category->slug!=null)
                                                    <li class=""><a href="{{ route('products.category', $category->slug) }}">{{ __($category->name) }}</a></li>
                                                @endif
                                            @endforeach
                                        @endif
                                            <li class="active"><a href="{{ route('products') }}">{{__('All Categories')}}</a></li>
                                        @if(isset($category_id) && !empty($category_id))
                                            {{-- <li class="active"><a href="{{ route('products.category', \App\Category::find($category_id)->slug) }}">{{ __(\App\Category::find($category_id)->name) }}</a></li>
                                            @foreach (\App\Category::find($category_id)->subcategories as $key2 => $subcategory)
                                                <li class="child"><a href="{{ route('products.subcategory', $subcategory->slug) }}">{{ __($subcategory->name) }}</a></li>
                                            @endforeach --}}
                                            <li class="active"><a href="{{ route('brands.get',['slug' => $brandSlug])}}">{{ __(\App\Category::find($category_id)->name) }}</a></li>
                                            @foreach (\App\Category::find($category_id)->subcategories as $key2 => $subcategory)
                                                <li class="child"><a href="{{ route('brands.cateogryGet',['slug' => $brandSlug,'categorySlug' => $subcategory->slug])}}">{{ __($subcategory->name) }}</a></li>
                                            @endforeach
                                        @else
                                            @foreach (\App\Category::get() as $key2 => $category)
                                                <li class="child"><a href="{{ route('brands.cateogryGet', ['slug' => $brandSlug, 'categorySlug' => $category->slug]) }}">{{ __($category->name) }}</a></li>
                                            @endforeach                                            
                                        @endif
                                        @if(isset($subcategory_id) && !empty($subcategory_id))
                                            <li class="active"><a href="{{ route('products') }}">{{__('All Categories')}}</a></li>
                                            <li class="active"><a href="{{ route('brands.cateogryGet', ['slug' => $brandSlug, 'categorySlug' => \App\SubCategory::find($subcategory_id)->category->slug]) }}">{{ __(\App\SubCategory::find($subcategory_id)->category->name) }}</a></li>
                                            <li class="active"><a href="{{ route('brands.cateogryGet', ['slug' => $brandSlug, 'categorySlug' => \App\SubCategory::find($subcategory_id)->slug]) }}">{{ __(\App\SubCategory::find($subcategory_id)->name) }}</a></li>
                                            @foreach (\App\SubCategory::find($subcategory_id)->subsubcategories as $key3 => $subsubcategory)
                                                <li class="child"><a href="{{ route('brands.cateogryGet', ['slug' => $brandSlug, 'categorySlug' => $subsubcategory->slug]) }}">{{ __($subsubcategory->name) }}</a></li>
                                            @endforeach
                                        @endif
                                        @if(isset($subsubcategory_id) && !empty($subsubcategory_id))
                                            <li class="active"><a href="{{ route('products') }}">{{__('All Categories')}}</a></li>
                                            <li class="active"><a href="{{ route('brands.cateogryGet', ['slug' => $brandSlug, 'categorySlug' => \App\SubsubCategory::find($subsubcategory_id)->subcategory->category->slug]) }}">{{ __(\App\SubSubCategory::find($subsubcategory_id)->subcategory->category->name) }}</a></li>
                                            <li class="active"><a href="{{ route('brands.cateogryGet', ['slug' => $brandSlug, 'categorySlug' => \App\SubsubCategory::find($subsubcategory_id)->subcategory->slug]) }}">{{ __(\App\SubsubCategory::find($subsubcategory_id)->subcategory->name) }}</a></li>
                                            <li class="current"><a href="{{ route('brands.cateogryGet', ['slug' => $brandSlug, 'categorySlug' => \App\SubsubCategory::find($subsubcategory_id)->slug]) }}">{{ __(\App\SubsubCategory::find($subsubcategory_id)->name) }}</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white sidebar-box mb-3">
                            @php
                                $product_brand=$brands->pluck('brand_id')->toArray();
                            @endphp
                            <div class="box-title text-center">
                                {{__('Filter by Brands')}}
                            </div>
                            <div class="box-content">
                                
                                    <select class="form-control sortSelect" data-placeholder="{{__('All Brands')}}" name="brand" onchange="filter()">
                                        <option value="">{{__('All Brands')}}</option>
                                        @foreach (\App\Brand::all() as $brand)
                                            @if (in_array($brand->id,$product_brand))
                                                <option value="{{ $brand->slug }}" @isset($brand_id) @if ($brand_id==$brand->id) selected @endif @endisset>{{ $brand->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {{-- @foreach (\App\Brand::all() as $key=>$brand)
                                        @if (in_array($brand->id,$product_brand))
                                            <div class="checkbox">
                                                <input type="radio" id="brand_{{$brand->id}}" name="brand" value="{{ $brand->id }}"  onchange="filter()">
                                                <label class="text-dark" for="brand_{{$brand->id}}">{{ $brand->name }}</label>
                                            </div>
                                        @endif
                                    @endforeach --}}
                                
                            </div>
                        </div>
                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                {{__('Price range')}}
                            </div>
                            <div class="box-content">
                                <div class="range-slider-wrapper mt-3">
                                    <!-- Range slider container -->
                                    @php
                                        $min = (\App\Product::min('unit_price'));
                                        $max = (\App\Product::max('unit_price'));
                                    @endphp
                                    <div id="input-slider-range" data-range-value-min="{{ $min }}" data-range-value-max="{{ $max }}"></div>

                                    <!-- Range slider values -->
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="range-slider-value value-low" @if (isset($min_price)) data-range-value-low="{{ $min_price }}" @elseif($products->min('unit_price') > 0)
                                                data-range-value-low="{{ $products->min('unit_price') }}"
                                                @else
                                                data-range-value-low="0"
                                                @endif
                                                id="input-slider-range-value-low">
                                        </div>

                                        <div class="col-6 text-right">
                                            <span class="range-slider-value value-high" @if (isset($max_price)) data-range-value-high="{{ $max_price }}" @elseif($products->max('unit_price') > 0)
                                                data-range-value-high="{{ $products->max('unit_price') }}"
                                                @else
                                                data-range-value-high="0"
                                                @endif
                                                id="input-slider-range-value-high">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($all_colors != null)
                            
                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                {{__('Filter by color')}}
                            </div>
                            <div class="box-content">
                                <!-- Filter by color -->
                                <ul class="list-inline checkbox-color checkbox-color-circle mb-0">
                                    @foreach ($all_colors as $key => $color)
                                    <li>
                                        <input type="radio" id="color-{{ $key }}" name="color" value="{{ $color }}" @if(isset($selected_color) && $selected_color==$color) checked @endif onchange="filter()">
                                        <label style="background: {{ $color }};" for="color-{{ $key }}" data-toggle="tooltip" data-original-title="{{ \App\Color::where('code', $color)->first()->name }}"></label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                            
                        @endif


                        @foreach ($attributes as $key => $attribute)
                        @if (\App\Attribute::find($attribute['id']) != null)
                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                Filter by {{ \App\Attribute::find($attribute['id'])->name }}
                            </div>
                            <div class="box-content">
                                <!-- Filter by others -->
                                <div class="filter-checkbox">
                                    @if(array_key_exists('values', $attribute))
                                    @foreach ($attribute['values'] as $key => $value)
                                    @php
                                    $flag = false;
                                    if(isset($selected_attributes)){
                                    foreach ($selected_attributes as $key => $selected_attribute) {
                                    if($selected_attribute['id'] == $attribute['id']){
                                    if(in_array($value, $selected_attribute['values'])){
                                    $flag = true;
                                    break;
                                    }
                                    }
                                    }
                                    }
                                    @endphp
                                    <div class="checkbox">
                                        <input type="checkbox" id="attribute_{{ $attribute['id'] }}_value_{{ $value }}" name="attribute_{{ $attribute['id'] }}[]" value="{{ $value }}" @if ($flag) checked @endif onchange="filter()">
                                        <label for="attribute_{{ $attribute['id'] }}_value_{{ $value }}">{{ $value }}</label>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        {{-- <button type="submit" class="btn btn-styled btn-block btn-base-4">Apply filter</button> --}}
                    </div>
                </div>
                
                <div class="col-xl-9">
                    <!-- <div class="bg-white"> -->
                    @if(isset($category_id) && !empty($category_id))
                    <input type="hidden" name="category" value="{{ \App\Category::find($category_id)->slug }}">
                    @endisset
                    @if(isset($subcategory_id) && !empty($subcategory_id))
                    <input type="hidden" name="subcategory" value="{{ \App\SubCategory::find($subcategory_id)->slug }}">
                    @endisset
                    @if(isset($subsubcategory_id) && !empty($subsubcategory_id))
                    <input type="hidden" name="subsubcategory" value="{{ \App\SubSubCategory::find($subsubcategory_id)->slug }}">
                    @endisset

                    <div class="sort-by-bar row no-gutters bg-white mb-3 px-3 pt-2">
                        {{-- <div class="col-xl-7 d-flex d-xl-block justify-content-between align-items-end ">
                            <div class="sort-by-box flex-grow-1">
                                <div class="form-group">
                                    <label>{{__('Search')}}</label>
                                    <div class="search-widget">
                                        <input class="form-control input-lg" type="text" name="q" placeholder="{{__('Search products')}}" @isset($query) value="{{ $query }}" @endisset>
                                        <button type="submit" class="btn-inner">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-xl-none ml-3 form-group">
                                <button type="button" class="btn p-1 btn-sm" id="side-filter">
                                    <i class="la la-filter la-2x"></i>
                                </button>
                            </div>
                        </div> --}}
                        {{-- <div class="col-xl-4 offset-xl-1"> --}}
                            <div class="row no-gutters">
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="sort-by-box px-1">
                                        <div class="form-group">
                                            <label>{{__('Sort by')}}</label>
                                            <select class="form-control sortSelect" data-minimum-results-for-search="Infinity" name="sort_by" onchange="filter()">
                                                <option value="1" @isset($sort_by) @if ($sort_by=='1' ) selected @endif @endisset>{{__('Newest')}}</option>
                                                <option value="2" @isset($sort_by) @if ($sort_by=='2' ) selected @endif @endisset>{{__('Oldest')}}</option>
                                                <option value="3" @isset($sort_by) @if ($sort_by=='3' ) selected @endif @endisset>{{__('Price low to high')}}</option>
                                                <option value="4" @isset($sort_by) @if ($sort_by=='4' ) selected @endif @endisset>{{__('Price high to low')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-lg-3 col-md-6 col-6">
                                    <div class="sort-by-box px-1">
                                        <div class="form-group">
                                            <label>{{__('Sellers')}}</label>
                                            <select class="form-control sortSelect" data-placeholder="{{__('All Sellers')}}" name="seller_id" onchange="filter()">
                                                <option value="">{{__('All Sellers')}}</option>
                                                @foreach (\App\Seller::all() as $key => $seller)
                                                @if ($seller->user != null && $seller->user->shop != null)
                                                <option value="{{ $seller->id }}" @isset($seller_id) @if ($seller_id==$seller->id) selected @endif @endisset>{{ $seller->user->shop->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-3 col-md-6 col-6">
                                    <div class="sort-by-box px-1">
                                        <div class="form-group">
                                            <label>{{__('Locations')}}</label>
                                            <select class="form-control sortSelect" data-placeholder="{{__('All Locations')}}" name="location" onchange="filter()">
                                                <option value="">{{__('All Locations')}}</option>
                                                @foreach (\App\Location::all() as $location)
                                                <option value="{{ $location->id }}" @isset($location_id) @if ($location_id==$location->id) selected @endif @endisset>{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        {{-- </div> --}}
                    </div>
                    <input type="hidden" name="min_price" value="">
                    <input type="hidden" name="max_price" value="">
                    <!-- <hr class=""> -->
                    <div class="products-box-bar p-3 bg-white">
                        <div class="row">
                            @if (isset($products) && !($products->isEmpty()))
                            @foreach ($products as $key => $product)
                            <div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6">
                                <div class="product-box-2 bg-white alt-box my-md-2">
                                    <div class="position-relative overflow-hidden">
                                        <a href="{{ route('product', $product->slug) }}" class="d-block product-image h-100 text-center" tabindex="0">
                                            @if (empty($product->featured_img))
                                            <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" alt="{{ __($product->name . '-' . $product->unit_price ) }}">
                                        @else
                                            <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->featured_img) }}" alt="{{ __($product->name . '-' . $product->unit_price ) }}">
                                        @endif
                                        {{-- @if (!empty($product->photos))
                                            @if (file_exists(json_decode($product->photos)[0]))                                                
                                                <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(json_decode($product->photos)[0]) }}" alt="{{ __($product->name) }}">                                                    
                                            @else                                                
                                                <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" alt="{{ __($product->name) }}">                                                    
                                            @endif
                                        @else
                                            <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" alt="{{ __($product->name) }}">
                                        @endif --}}
                                        </a>
                                        <div class="product-btns clearfix">
                                            <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" type="button">
                                                <i class="la la-heart-o"></i>
                                            </button>
                                            <button class="btn add-compare" title="Add to Compare" onclick="addToCompare({{ $product->id }})" type="button">
                                                <i class="la la-refresh"></i>
                                            </button>
                                            <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal({{ $product->id }})" type="button">
                                                <i class="la la-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-md-3 p-2">
                                        <span class="product-price strong-600" style="font-size: 15px">{{ home_discounted_base_price($product->id) }}</span>

                                        <div class="price-box d-flex align-items-center">
                                            @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                            @endif
                                            @if (! $product->discount == 0)
                                                <div>
                                                    {{ ($product->discount_type == 'amount')?'  Rs.':'' }} -{{ ($product->discount) }}{{ !($product->discount_type == 'amount')?' %':'' }}
                
                                                </div>
                                            @endif
                                        </div>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                        <h2 class="product-title p-0">
                                            <a href="{{ route('product', $product->slug) }}" class=" text-truncate">{{ __($product->name) }}</a>
                                        </h2>
                                        @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                            {{ __('Club Point') }}:
                                            <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="nothing-wrap d-flex justify-content-center w-100 align-items-center flex-column">
                                <p class="pt-3">Sorry, Nothing to show here.</p>
                                <img src="{{asset('img/client-images/Add to Cart-amico.svg')}}" class="nothing">
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="products-pagination bg-white p-3">
                        <nav aria-label="Center aligned pagination">
                            <ul class="pagination justify-content-center">
                                {{ $products->links() }}
                            </ul>
                        </nav>
                    </div>

                    <!-- </div> -->
                </div>
            </div>
        </form>
    </div>
</section>

@endsection

@section('script')
<script type="text/javascript">
    function filter() {
        $('#search-form').submit();
    }

    function rangefilter(arg) {
        $('input[name=min_price]').val(arg[0]);
        $('input[name=max_price]').val(arg[1]);
        filter();
    }
</script>
@endsection