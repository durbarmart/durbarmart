@extends('frontend.layouts.app')

@foreach ($pages as $page)
    

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ asset($page->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ asset($page->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($page->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $page->slug) }}" />
    <meta property="og:image" content="{{ asset($page->meta_img) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:price:amount" content="{{ single_price($page->unit_price) }}" />
@endsection

@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="breadcrumb">
                    <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                    <li><a href="{{ route('custom-pages.show_custom_page', $page->slug) }}">{{__($page->title)}}</a></li>
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
                                {{__('Custom Pages')}}
                            </div>
                            
                            <div class="box-content">
                                <div class="category-filter">
                                    <ul>
                                        @foreach(\App\Page::all() as $page_list)
                                        <li class=""><a href="{{ route('custom-pages.show_custom_page', $page_list->slug) }}">{{ __($page_list->title) }}</a></li>
                                        @endforeach
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-9 ">
                    {!! $page->content !!}
                </div>
            </div>
        </form>
    </div>
</section>
@if (!empty($page->category_id))
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-lg-left">
                    <span class="mr-4">
                        <?php
                            $category=\App\Category::where('id',$page->category_id)->first();
                            ?>
                        {{$category->name}}
                    </span>
                </h3>
                <ul class="inline-links float-lg-right nav mt-3 mb-2 m-lg-0">
                    <li><a href="{{ route('products.category', $category->slug) }}" class="active">View More</a></li>
                    
                </ul>
            </div>
            <?php 
                $pages=\App\Page::where('id',$page->id)->first();
                $array = explode('!!', $pages->product_id);
                
            ?>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @foreach ($array as $key => $arr)
                    <?php $product=\App\Product::where('id',$array[$key])->first(); ?>
                    <div class="caorusel-card">
                        <div class="product-box-2 bg-white alt-box my-2">
                            <div class="position-relative overflow-hidden">
                                <a href="{{ route('product', $product->slug) }}" class="d-block product-image h-100 text-center">
                                    <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(json_decode($product->photos)[0]) }}" alt="{{ __($product->name) }}">
                                </a>
                                <div class="product-btns clearfix">
                                    <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" tabindex="0">
                                        <i class="la la-heart-o"></i>
                                    </button>
                                    <button class="btn add-compare" title="Add to Compare" onclick="addToCompare({{ $product->id }})" tabindex="0">
                                        <i class="la la-refresh"></i>
                                    </button>
                                    <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal({{ $product->id }})" tabindex="0">
                                        <i class="la la-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="p-md-3 p-2">
                                <div class="price-box">
                                    @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                    @endif
                                    <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
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
                </div>
            </div>

        </div>
    </div>
</section>
@endif

@if (!empty($page->brand_id))    
<?php 
    $brand=\App\Brand::where('id',$page->brand_id)->first();
?>
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-lg-left">
                    <span class="mr-4">
                        Products From {{$brand->name}}
                    </span>
                </h3>
                <ul class="inline-links float-lg-right nav mt-3 mb-2 m-lg-0">
                    <li><a href="{{ route('brands.all') }}" class="active">View More</a></li>
                </ul>
            </div>
            <?php 
                $products=\App\Product::where('brand_id',$page->brand_id)->get();
            ?>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @foreach ($products as $key => $product)
                    <div class="caorusel-card">
                        <div class="product-box-2 bg-white alt-box my-2">
                            <div class="position-relative overflow-hidden">
                                <a href="{{ route('product', $product->slug) }}" class="d-block product-image h-100 text-center">
                                    <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(json_decode($product->photos)[0]) }}" alt="{{ __($product->name) }}">
                                </a>
                                <div class="product-btns clearfix">
                                    <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" tabindex="0">
                                        <i class="la la-heart-o"></i>
                                    </button>
                                    <button class="btn add-compare" title="Add to Compare" onclick="addToCompare({{ $product->id }})" tabindex="0">
                                        <i class="la la-refresh"></i>
                                    </button>
                                    <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal({{ $product->id }})" tabindex="0">
                                        <i class="la la-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="p-md-3 p-2">
                                <div class="price-box">
                                    @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                    @endif
                                    <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
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
                </div>
            </div>

        </div>
    </div>
</section>
@endif

@if(!empty($page->seller_id))
<?php 
    $user=\App\User::where('id',$page->seller_id)->first();
    $products=\App\Product::where('user_id',$user->id)->get();
?>
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-lg-left">
                    <span class="mr-4">
                        Products From {{$user->name}}
                    </span>
                </h3>
                {{-- <ul class="inline-links float-lg-right nav mt-3 mb-2 m-lg-0">
                    <li><a href="{{ route('products.category', $category->slug) }}" class="active">View More</a></li>
                </ul> --}}
            </div>
            
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @foreach ($products as $key => $product)
                    <div class="caorusel-card">
                        <div class="product-box-2 bg-white alt-box my-2">
                            <div class="position-relative overflow-hidden">
                                <a href="{{ route('product', $product->slug) }}" class="d-block product-image h-100 text-center">
                                    <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(json_decode($product->photos)[0]) }}" alt="{{ __($product->name) }}">
                                </a>
                                <div class="product-btns clearfix">
                                    <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" tabindex="0">
                                        <i class="la la-heart-o"></i>
                                    </button>
                                    <button class="btn add-compare" title="Add to Compare" onclick="addToCompare({{ $product->id }})" tabindex="0">
                                        <i class="la la-refresh"></i>
                                    </button>
                                    <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal({{ $product->id }})" tabindex="0">
                                        <i class="la la-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="p-md-3 p-2">
                                <div class="price-box">
                                    @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                    @endif
                                    <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
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
                </div>
            </div>

        </div>
    </div>
</section> 
   
@endif

@endsection
@endforeach