<style>
    .dropdown-custom-category .dropdown-menu {
        position: relative !important;
        transform: unset !important;
        max-height: 300px;
        overflow-y: scroll;
        border: 0;
        box-shadow: rgb(237 237 237) 0px 8px 24px;
        width: 100%;
        margin-bottom: 10px;

    }

    .dropdown-custom-category .dropdown-menu .nav .nav-item {
        text-align: left;
    }

    .dropdown-custom-category .dropdown-menu .nav .nav-link {
        font-size: 0.7rem;
        color: #767676 !important;
    }

    .dropdown-custom-category .dropdown-menu .nav {
        padding: 5px 0px;
    }
</style>

<nav class="navbar header navbar-expand-lg p-0">
    <div class="row m-0 w-100">
        <div class="col-12 p-0">
            <header class="section-header top-header-bg d-md-block d-none">
                <div class="container">
                    <div class="top-header d-flex justify-content-end align-items-center">
                        <div class="top-social-icon">
                            <ul class="mb-0">
                                <li>
                                    <a href="{{ route('orders.track') }}" class="text-dark">{{__('Track Order')}}</a>
                                    {{-- @if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated)
                                    <a href="{{ route('affiliate.apply') }}" class="text-dark">{{__('Be an affiliate partner')}}</a>
                                    @endif --}}
                                    @auth
                                    <a href="{{ route('dashboard') }}" class="text-dark">{{__('My Panel')}}</a>
                                    <a href="{{ route('logout') }}" class="text-dark">{{__('Logout')}}</a>
                                    @else
                                    <a href="{{ route('user.login') }}" class="text-dark">{{__('Login')}}</a>
                                    <a href="{{ route('user.registration') }}" class="text-dark">{{__('Registration')}}</a>
                                    @endauth
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
        </div>
        <div class="col-12 header-content py-1 p-0">
            <div class="container">
                <div class="header-logo text-center d-flex justify-content-between align-items-center">
                    <a class="navbar-brand text-white text-uppercase text-left p-0" href="{{ route('home') }}">
                        @php
                        $generalsetting = \App\GeneralSetting::first();
                        @endphp
                        @if($generalsetting->logo != null)
                        <img src="{{ asset($generalsetting->logo) }}" class="img-fluid" alt="{{ env('APP_NAME') }}">
                        @else
                        <img src="{{ asset('frontend/images/logo/logo.png') }}" class="img-fluid" alt="{{ env('APP_NAME') }}">
                        @endif
                    </a>
                    <!-- search start  -->
                    <div class="searchbar d-md-block d-none" style="border-radius:10px">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="d-flex position-relative">

                                <div class="w-100">
                                    <input class="search_input" type="text" aria-label="Search" id="search" name="q" placeholder="Search..." autocomplete="off" />
                                </div>
                                <button type="submit" class="search_icon" style="border-radius: 0 10px 10px 0px;"><i class="fa fa-search"></i></button>
                                
                                <div class="typed-search-box d-none">
                                    <div class="search-preloader">
                                        <div class="loader">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </div>
                                    <div class="search-nothing d-none">
                                    </div>
                                    <div id="search-content">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <!-- search end  -->
                <!-- search mobile new star  -->
                <div class="search_mobile_men d-md-none d-block">
                    <button class="search_icon_new">
                        <i class="fa fa-search"></i>
                    </button>
                    <div class="sub_search">
                        <form action="{{ route('search') }}" method="GET" class="d-flex m-0">
                            <input class="search_input input_box" type="text" aria-label="Search" id="search_mob" name="q" placeholder="Search..." autocomplete="off" />
                            <button class="search_top" type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            <div class="type-search-box d-none">
                                <div class="search-preloader">
                                    <div class="loader">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="search-nothing d-none">
                                </div>
                                <div id="mob_search-content">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- search mobile new end  -->
                <div class="logo-bar-icons d-md-flex justify-content-center align-items-center d-none">
                    {{-- <div class="d-inline-block d-lg-none">
                       <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                      <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                    </a>
                    </div>
                    </div> --}}
                    <div class="d-none d-lg-inline-block">
                        <div class="nav-compare-box" id="compare">
                            <a href="{{ route('compare') }}" class="nav-box-link" style="padding-right: 20px;">
                                <img data-toggle="tooltip" data-placement="top" title="Compare" src="{{asset('frontend/images/coma.svg')}}" alt="cart-logo" class="img-fluid img_sag">
                                {{-- <span class="nav-box-text d-none d-xl-inline-block">{{__('Compare')}}</span> --}}
                                @if(Session::has('compare'))
                                <sup class="nav-box-number">{{ count(Session::get('compare'))}}</sup>
                                @else
                                <sup class="nav-box-number">0</sup>
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-lg-inline-block">
                        <div class="nav-wishlist-box" id="wishlist">
                            <a href="{{ route('wishlists.index') }}" class="nav-box-link" style="padding-right: 20px;">
                                <!-- <i class="fa fa-heart text-dark"></i> -->
                                <img data-toggle="tooltip" data-placement="top" title="Wishlist" src="{{asset('frontend/images/hort.svg')}}" alt="cart-logo" class="img-fluid img_sag">
                                {{-- <span class="nav-box-text d-none d-xl-inline-block">{{__('Wishlist')}}</span> --}}
                                @if(Auth::check())
                                <sup class="nav-box-number">{{ count(Auth::user()->wishlists)}}</sup>
                                @else
                                <sup class="nav-box-number">0</sup>
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-lg-inline-block" data-hover="dropdown">
                        <div class="nav-cart-box dropdown" id="cart_items">
                            <a href="" class="nav-box-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- <i class="fa fa-shopping-cart text-dark"></i> -->
                                <img data-toggle="tooltip" data-placement="top" title="Cart" src="{{asset('frontend/images/b15beedcaf38913a9969b50753dd2aa1.svg')}}" alt="cart-logo" class="img-fluid img_sag">
                                {{-- <span class="nav-box-text d-none d-xl-inline-block">{{__('Cart')}}</span> --}}
                                @if(Session::has('cart'))
                                <sup class="nav-box-number">{{ count(Session::get('cart'))}}</sup>
                                @else
                                <sup class="nav-box-number">0</sup>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right px-0">
                                <li>
                                    <div class="dropdown-cart px-0">
                                        @if(Session::has('cart'))
                                        @if(count($cart = Session::get('cart')) > 0)
                                        <div class="dc-header">
                                            <h3 class="heading heading-6 strong-700">{{__('Cart Items')}}</h3>
                                        </div>
                                        <div class="dropdown-cart-items c-scrollbar">
                                            @php
                                            $total = 0;
                                            @endphp
                                            @foreach($cart as $key => $cartItem)
                                            @php
                                            $product = \App\Product::find($cartItem['id']);
                                            $total = $total + $cartItem['price']*$cartItem['quantity'];
                                            @endphp
                                            <div class="dc-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="dc-image">
                                                        <a href="{{ route('product', $product->slug) }}">
                                                            @if (file_exists($product->featured_img)) 
                                                               <img class="img-fluid lazyload" src="{{ asset($product->featured_img) }}" data-src="{{ asset($product->featured_img) }}" alt="{{ __($product->name) }}">
                                                            @else
                                                               <img class="img-fluid lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" alt="{{ __($product->name) }}">
                                                            @endif
                                                            {{-- <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(json_decode($product->photos)[0]) }}" class="img-fluid lazyload" alt="{{ __($product->name) }}"> --}}
                                                        </a>
                                                    </div>
                                                    <div class="dc-content">
                                                        <span class="d-block dc-product-name text-capitalize strong-600 mb-1">
                                                            <a href="{{ route('product', $product->slug) }}">
                                                                {{ __($product->name) }}
                                                            </a>
                                                        </span>
                                                        <span class="dc-quantity">x{{ $cartItem['quantity'] }}</span>
                                                        <span class="dc-price">{{ single_price($cartItem['price']*$cartItem['quantity']) }}</span>
                                                    </div>
                                                    <div class="dc-actions">
                                                        <button onclick="removeFromCart({{ $key }})">
                                                            <i class="la la-close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="dc-item py-3">
                                            <span class="subtotal-text">{{__('Subtotal')}}</span>
                                            <span class="subtotal-amount">{{ single_price($total) }}</span>
                                        </div>
                                        <div class="py-2 text-center dc-btn">
                                            <ul class="inline-links inline-links--style-3">
                                                <li class="px-1">
                                                    <a href="{{ route('cart') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1">
                                                        <i class="fa fa-shopping-cart text-dark"></i> {{__('View cart')}}
                                                    </a>
                                                </li>
                                                @if (Auth::check())
                                                <li class="px-1">
                                                    <a href="{{ route('checkout.shipping_info') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1 light-text">
                                                        <i class="la la-mail-forward"></i> {{__('Checkout')}}
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                        @else
                                        <div class="dc-header">
                                            <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                        </div>
                                        @endif
                                        @else
                                        <div class="dc-header">
                                            <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
    <a class="hamburger_icon d-lg-none d-block" onclick="sideMenuOpen(this)">
        <div class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </a>
    <!-- Button trigger modal -->
</nav>
<!-- Mobile Nav start -->
<div class="mobile-side-menu d-lg-none">
    <div class="side-menu-overlay opacity-0" onclick="sideMenuClose()"></div>
    <div class="side-menu-wrap opacity-0">
        <div class="side-menu closed">
            <div class="side-menu-header ">
                <div class="side-menu-close" onclick="sideMenuClose()">
                    <i class="la la-close"></i>
                </div>
                @auth
                    <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                        @if (Auth::user()->avatar_original != null)
                        <div class="image " style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
                        @else
                        <div class="image " style="background-image:url('{{ asset('frontend/images/user.png') }}')"></div>
                        @endif
                        <div class="name">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="side-login px-3 pb-3">
                        <a href="{{ route('logout') }}">{{__('Sign Out')}}</a>
                    </div>
                    @else
                    <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                        <div class="image " style="background-image:url('{{ asset('frontend/images/icons/user-placeholder.jpg') }}')"></div>
                    </div>
                    <div class="side-login px-3 pb-3">
                        <a href="{{ route('user.login') }}">{{__('Sign In')}}</a>
                        <a href="{{ route('user.registration') }}">{{__('Registration')}}</a>
                    </div>
                @endauth
            </div>
            <div class="side-menu-list px-3">
                <ul class="side-user-menu">
                    <li class="dropdown-custom-category">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: rgb(72, 77, 103);">
                            <i class="fa fa-eercast"></i>
                            <span>Category</span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="container d-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="nav flex-column p-0">
                                            @foreach (\App\Category::where('type', 'Category')->get() as $key => $category)
                                            <li class="nav-item p-0">
                                                <a class="nav-link head font-weight-bold" data-toggle="collapse" href=".collapse{{$category->id}}" role="button" aria-expanded="false" aria-controls="collapseExample" style="color: rgb(72, 77, 103);"> <span><i class="fa fa-minus"></i></span> {{$category->name}}</a>
                                                <div class="collapse collapse{{$category->id}}">
                                                    @foreach($category->subcategories as $subcategory)
                                                    <a class="nav-item p-0">
                                                        <a class="nav-link head font-weight-bold" data-toggle="collapse1" href="{{ url('/') }}/search?subcategory={{ $subcategory->slug }}" role="button" aria-expanded="false" aria-controls="collapseExample" style="color: rgb(72, 77, 103); padding-left: 20px;"> <span><i class="fa fa-minus"></i></span> {{$subcategory->name}}</a>
                                                        <div class="collapse1 collapse{{$subcategory->id}}">
                                                            @foreach($subcategory->subsubcategories as $subsubcategory)
                                                            <a class="nav-item p-0">
                                                                <a class="nav-link" href="{{ url('/') }}/search?subsubcategory={{ $subsubcategory->slug }}" style="color: rgb(72, 77, 103); padding-left: 40px;">{{$subsubcategory->name}}</a>
                                                            </a>
                                                            @endforeach
                                                        </div>
                                                    </a>
                                                    @endforeach
                                                </div>
                                            </li>
                                            @endforeach

                                        </ul>
                                    </div>

                                    <!-- /.col-md-12  -->
                                </div>
                            </div>
                            <!--  /.container  -->
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="la la-home"></i>
                            <span>{{__('Home')}}</span>
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="la la-dashboard"></i>
                            <span>{{__('Dashboard')}}</span>
                        </a>
                    </li>
                    @endauth
                    @auth
                    <li>
                        <a href="{{ route('purchase_history.index') }}">
                            <i class="la la-file-text"></i>
                            <span>{{__('Purchase History')}}</span>
                        </a>
                    </li>
                    @endauth
                    @auth
                    @php
                    $conversation = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', '1')->get();
                    @endphp
                    @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                    <li>
                        <a href="{{ route('conversations.index') }}" class="{{ areActiveRoutesHome(['conversations.index', 'conversations.show'])}}">
                            <i class="la la-comment"></i>
                            <span class="category-name">
                                {{__('Conversations')}}
                                @if (count($conversation) > 0)
                                <span class="ml-2" style="color:green"><strong>({{ count($conversation) }})</strong></span>
                                @endif
                            </span>
                        </a>
                    </li>
                    @endif
                    @endauth
                    <li>
                        <a href="{{ route('compare') }}">
                            <i class="la la-refresh"></i>
                            <span>{{__('Compare')}}</span>
                            @if(Session::has('compare'))
                            <span class="badge" id="compare_items_sidenav">{{ count(Session::get('compare'))}}</span>
                            @else
                            <span class="badge" id="compare_items_sidenav">0</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cart') }}">
                            <i class="fa fa-shopping-cart text-dark"></i>
                            <span>{{__('Cart')}}</span>
                            @if(Session::has('cart'))
                            <span class="badge" id="cart_items_sidenav">{{ count(Session::get('cart'))}}</span>
                            @else
                            <span class="badge" id="cart_items_sidenav">0</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wishlists.index') }}">
                            <i class="la la-heart-o"></i>
                            <span>{{__('Wishlist')}}</span>
                        </a>
                    </li>
                    @auth
                    @if(\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1)
                    <li>
                        <a href="{{ route('customer_products.index') }}">
                            <i class="la la-diamond"></i>
                            <span>{{__('Classified Products')}}</span>
                        </a>
                    </li>
                    @endif
                    @endauth
                    @auth
                    @if (\App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
                    <li>
                        <a href="{{ route('wallet.index') }}">
                            <i class="la la-dollar"></i>
                            <span>{{__('My Wallet')}}</span>
                        </a>
                    </li>
                    @endif
                    @endauth
                    @auth
                    <li>
                        <a href="{{ route('profile') }}">
                            <i class="la la-user"></i>
                            <span>{{__('Manage Profile')}}</span>
                        </a>
                    </li>
                    @endauth
                    @php
                    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                    $club_point_addon = \App\Addon::where('unique_identifier', 'club_point')->first();
                    @endphp
                    @auth
                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                    <li>
                        <a href="{{ route('customer_refund_request') }}" class="{{ areActiveRoutesHome(['customer_refund_request'])}}">
                            <i class="la la-file-text"></i>
                            <span class="category-name">
                                {{__('Sent Refund Request')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    @endauth
                    @auth
                    @if ($club_point_addon != null && $club_point_addon->activated == 1)
                    <li>
                        <a href="{{ route('earnng_point_for_user') }}" class="{{ areActiveRoutesHome(['earnng_point_for_user'])}}">
                            <i class="la la-dollar"></i>
                            <span class="category-name">
                                {{__('Earning Points')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    @endauth
                    @auth
                    <li>
                        <a href="{{ route('support_ticket.index') }}" class="{{ areActiveRoutesHome(['support_ticket.index', 'support_ticket.show'])}}">
                            <i class="la la-support"></i>
                            <span class="category-name">
                                {{__('Support Ticket')}}
                            </span>
                        </a>
                    </li>
                    @endauth
                </ul>
                @if (Auth::check() && Auth::user()->user_type == 'seller')
                <div class="sidebar-widget-title py-0">
                    <span>{{__('Shop Options')}}</span>
                </div>
                <ul class="side-seller-menu">
                    <li>
                        <a href="{{ route('seller.products') }}">
                            <i class="la la-diamond"></i>
                            <span>{{__('Products')}}</span>
                        </a>
                    </li>
                    @if (\App\Addon::where('unique_identifier', 'pos_system')->first() != null && \App\Addon::where('unique_identifier', 'pos_system')->first()->activated)
                    <li>
                        <a href="{{route('poin-of-sales.seller_index')}}">
                            <i class="la la-fax"></i>
                            <span>
                                {{__('POS Manager')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('orders.index') }}">
                            <i class="la la-file-text"></i>
                            <span>{{__('Orders')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shops.index') }}">
                            <i class="la la-cog"></i>
                            <span>{{__('Shop Setting')}}</span>
                        </a>
                    </li>
                    <div class="logo-bar-icons d-inline-block ml-auto">
                        <div class="d-inline-block d-lg-none">
                            <div class="nav-search-box">
                                <a href="#" class="nav-box-link">
                                    <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                                </a>
                            </div>
                        </div>
                        <div class="d-none d-lg-inline-block">
                            <div class="nav-compare-box" id="compare">
                                <a href="{{ route('compare') }}" class="nav-box-link">
                                    <i class="la la-refresh d-inline-block nav-box-icon"></i>
                                    <span class="nav-box-text d-none d-xl-inline-block">{{__('Compare')}}</span>
                                    @if(Session::has('compare'))
                                    <span class="nav-box-number">{{ count(Session::get('compare'))}}</span>
                                    @else
                                    <span class="nav-box-number">0</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="d-none d-lg-inline-block">
                            <div class="nav-wishlist-box" id="wishlist">
                                <a href="{{ route('wishlists.index') }}" class="nav-box-link">
                                    <i class="la la-heart-o d-inline-block nav-box-icon"></i>
                                    <span class="nav-box-text d-none d-xl-inline-block">{{__('Wishlist')}}</span>
                                    @if(Auth::check())
                                    <span class="nav-box-number">{{ count(Auth::user()->wishlists)}}</span>
                                    @else
                                    <span class="nav-box-number">0</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="d-inline-block" data-hover="dropdown">
                            <div class="nav-cart-box dropdown" id="cart_items">
                                <a href="" class="nav-box-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="la la-shopping-cart d-inline-block nav-box-icon"></i>
                                    <span class="nav-box-text d-none d-xl-inline-block">{{__('Cart')}}</span>
                                    @if(Session::has('cart'))
                                    <span class="nav-box-number">{{ count(Session::get('cart'))}}</span>
                                    @else
                                    <span class="nav-box-number">0</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right px-0">
                                    <li>
                                        <div class="dropdown-cart px-0">
                                            @if(Session::has('cart'))
                                            @if(count($cart = Session::get('cart')) > 0)
                                            <div class="dc-header">
                                                <h3 class="heading heading-6 strong-700">{{__('Cart Items')}}</h3>
                                            </div>
                                            <div class="dropdown-cart-items c-scrollbar">
                                                @php
                                                $total = 0;
                                                @endphp
                                                @foreach($cart as $key => $cartItem)
                                                @php
                                                $product = \App\Product::find($cartItem['id']);
                                                $total = $total + $cartItem['price']*$cartItem['quantity'];
                                                @endphp
                                                <div class="dc-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="dc-image">
                                                            <a href="{{ route('product', $product->slug) }}">
                                                                <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->thumbnail_img) }}" class="img-fluid lazyload" alt="{{ __($product->name . '-' . $product->unit_price ) }}">
                                                            </a>
                                                        </div>
                                                        <div class="dc-content">
                                                            <span class="d-block dc-product-name text-capitalize strong-600 mb-1">
                                                                <a href="{{ route('product', $product->slug) }}">
                                                                    {{ __($product->name) }}
                                                                </a>
                                                            </span>
                                                            <span class="dc-quantity">x{{ $cartItem['quantity'] }}</span>
                                                            <span class="dc-price">{{ single_price($cartItem['price']*$cartItem['quantity']) }}</span>
                                                        </div>
                                                        <div class="dc-actions">
                                                            <button onclick="removeFromCart({{ $key }})">
                                                                <i class="la la-close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="dc-item py-3">
                                                <span class="subtotal-text">{{__('Subtotal')}}</span>
                                                <span class="subtotal-amount">{{ single_price($total) }}</span>
                                            </div>
                                            <div class="py-2 text-center dc-btn">
                                                <ul class="inline-links inline-links--style-3">
                                                    <li class="px-1">
                                                        <a href="{{ route('cart') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1">
                                                            <i class="la la-shopping-cart"></i> {{__('View cart')}}
                                                        </a>
                                                    </li>
                                                    @if (Auth::check())
                                                    <li class="px-1">
                                                        <a href="{{ route('checkout.shipping_info') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1 light-text">
                                                            <i class="la la-mail-forward"></i> {{__('Checkout')}}
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @else
                                            <div class="dc-header">
                                                <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                            </div>
                                            @endif
                                            @else
                                            <div class="dc-header">
                                                <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                            </div>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="hover-category-menu" id="hover-category-menu">
    <div class="container">
        <div class="row no-gutters position-relative">
            <div class="col-lg-3 position-static">
                <div class="category-sidebar" id="category-sidebar">
                    <div class="all-category">
                        <span>{{__('CATEGORIES')}}</span>
                        <a href="{{ route('categories.all') }}" class="d-inline-block">See All ></a>
                    </div>
                    <ul class="categories">
                        @foreach (\App\Category::all()->take(11) as $key => $category)
                        @php
                        $brands = array();
                        @endphp
                        <li class="category-nav-element" data-id="{{ $category->id }}">
                            <a href="{{ route('products.category', $category->slug) }}">
                                <img class="cat-imag lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->icon) }}" width="30" alt="{{ __($category->name) }}">
                                <span class="cat-name">{{ __($category->name) }}</span>
                            </a>
                            @if(count($category->subcategories)>0)
                            <div class="sub-cat-menu c-scrollbar">
                                <div class="c-preloader">
                                    <i class="fa fa-spin fa-spinner"></i>
                                </div>
                            </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @php
            $conversation = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
            @endphp
            @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
            <li>
                <a href="{{ route('conversations.index') }}" class="{{ areActiveRoutesHome(['conversations.index', 'conversations.show'])}}">
                    <i class="la la-comment"></i>
                    <span class="category-name">
                        {{__('Conversations')}}
                        @if (count($conversation) > 0)
                        <span class="ml-2" style="color:green"><strong>({{ count($conversation) }})</strong></span>
                        @endif
                    </span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('payments.index') }}">
                    <i class="la la-cc-mastercard"></i>
                    <span>{{__('Payment History')}}</span>
                </a>
            </li>
            </ul>
            <div class="sidebar-widget-title py-0">
                <span>{{__('Earnings')}}</span>
            </div>
            <div class="widget-balance py-3">
                <div class="text-center">
                    <div class="heading-4 strong-700 mb-4">
                        @php
                        $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-30d'))->get();
                        $total = 0;
                        foreach ($orderDetails as $key => $orderDetail) {
                        if($orderDetail->order != null && $orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                        $total += $orderDetail->price;
                        }
                        }
                        @endphp
                        <small class="d-block text-sm alpha-5 mb-2">{{__('Your earnings (current month)')}}</small>
                        <span class="p-2 bg-base-1 rounded">{{ single_price($total) }}</span>
                    </div>
                    <table class="text-left mb-0 table w-75 m-auto">
                        <tbody>
                            <tr>
                                @php
                                $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
                                $total = 0;
                                foreach ($orderDetails as $key => $orderDetail) {
                                if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                                $total += $orderDetail->price;
                                }
                                }
                                @endphp
                                <td class="p-1 text-sm">
                                    {{__('Total earnings')}}:
                                </td>
                                <td class="p-1">
                                    {{ single_price($total) }}
                                </td>
                            </tr>
                            <tr>
                                @php
                                $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-60d'))->where('created_at', '<=', date('-30d'))->get();
                                    $total = 0;
                                    foreach ($orderDetails as $key => $orderDetail) {
                                    if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                                    $total += $orderDetail->price;
                                    }
                                    }
                                    @endphp
                                    <td class="p-1 text-sm">
                                        {{__('Last Month earnings')}}:
                                    </td>
                                    <td class="p-1">
                                        {{ single_price($total) }}
                                    </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            {{-- <!-- <div class="sidebar-widget-title py-0">
                  <span>Categories</span>
              </div>
              <ul class="side-seller-menu">
                  @foreach (\App\Category::all() as $key => $category)
                      <li>
                      <a href="{{ route('products.category', $category->slug) }}" class="text-truncate">
                          <img class="cat-image lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->icon) }}" width="13" alt="{{ __($category->name) }}">
                          <span>{{ __($category->name) }}</span>
                      </a>
                  </li>
                  @endforeach
              </ul> --> --}}
        </div>
    </div>
</div>
</div>
<!-- Mobile Nav end -->