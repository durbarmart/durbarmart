
@php
$generalsetting = \App\GeneralSetting::first();
$color = \App\Color::where('id',$generalsetting->frontend_color)->first();

@endphp
<a href="" class='button_chat'>chat</a>
<a href="#" class='top_btn hidden'><i class="fa fa-angle-up"></i></a>

<!-- FOOTER -->
<footer id="footer" class="footer" style="background:@if($color!=null) {{$color->code}} @endif">
<div class="footer-top">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            
            <div class="col-lg-3  text-center text-md-left mb-3 mb-md-0">
                <div class="col">
                    <a href="{{ route('home') }}" class="d-block">
                        @if($generalsetting->logo != null)
                        <img loading="lazy" src="{{ asset($generalsetting->logo) }}" alt="{{ env('APP_NAME') }}" height="44">
                        @else
                        <img loading="lazy" src="{{ asset('frontend/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" height="44">
                        @endif
                    </a>
                    <p class="mt-3">{{ $generalsetting->description }}</p>
                    <ul class="footer-links contact-widget mb-3">
                        <li>
                            <span class="d-block" style="color: white">{{__('Address')}}:</span>
                            <span class="d-block">{{ $generalsetting->address }}</span>
                        </li>
                        <li>
                            <span class="d-block" style="color: white">{{__('Phone')}}:</span>
                            <span class="d-block">{{ $generalsetting->phone }}</span>
                        </li>
                        <li>
                            {{-- opacity-5 --}}
                            <span class="d-block" style="color: white">{{__('Email')}}:</span>
                            <span class="d-block">
                                <a style="color: white" href="mailto:{{ $generalsetting->email }}">{{ $generalsetting->email  }}</a>
                            </span>
                        </li>
                    </ul>
                    <ul class="text-center my-3 my-md-0 social-nav model-2 d-flex justify-content-md-start justify-content-center">
                        @if ($generalsetting->facebook != null)
                        <li>
                            <a href="{{ $generalsetting->facebook }}" class="facebook" target="_blank" data-toggle="tooltip" data-original-title="Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        @endif
                        @if ($generalsetting->instagram != null)
                        <li>
                            <a href="{{ $generalsetting->instagram }}" class="instagram" target="_blank" data-toggle="tooltip" data-original-title="Instagram">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                        @endif
                        @if ($generalsetting->twitter != null)
                        <li>
                            <a href="{{ $generalsetting->twitter }}" class="twitter" target="_blank" data-toggle="tooltip" data-original-title="Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        @endif
                        @if ($generalsetting->youtube != null)
                        <li>
                            <a href="{{ $generalsetting->youtube }}" class="youtube" target="_blank" data-toggle="tooltip" data-original-title="Youtube">
                                <i class="fa fa-youtube"></i>
                            </a>
                        </li>
                        @endif
                        @if ($generalsetting->google_plus != null)
                        <li>
                            <a href="{{ $generalsetting->google_plus }}" class="google-plus" target="_blank" data-toggle="tooltip" data-original-title="Google Plus">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 mb-4 mb-md-0">
                <div class="col text-center text-md-left">
                    <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                        {{__('Useful Link')}}
                    </h4>
                    <ul class="footer-links">
                        <li>
                            <a class="nav-link" href="{{ route('shops.create') }}">{{__('Become a vendor')}}</a>
                        </li>

                        @foreach (\App\Link::all() as $key => $link)
                        <li>
                            <a href="{{ $link->url }}" title="">
                                {{ $link->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 mb-4 mb-md-0">
                <div class="col text-center text-md-left">
                    <h3 class="heading heading-xs strong-600 text-uppercase mb-2">
                        {{__('My Account')}}
                    </h3>
                    <ul class="footer-links">
                        @if (Auth::check())
                        <li>
                            <a href="{{ route('logout') }}" title="Logout">
                                {{__('Logout')}}
                            </a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('user.login') }}" title="Login">
                                {{__('Login')}}
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('purchase_history.index') }}" title="Order History">
                                {{__('Order History')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('wishlists.index') }}" title="My Wishlist">
                                {{__('My Wishlist')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.track') }}" title="Track Order">
                                {{__('Track Order')}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class=" col-lg-3 ">
                <div class="col text-center text-md-left">
                    <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                        {{__('News Letter')}}
                    </h4>


                </div>
                @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                <div class="col text-center text-md-left">
                    <div class="mt-3">

                        <div class="d-inline-block d-md-block">
                            <form class="form-inline" method="POST" action="{{ route('subscribers.store') }}">
                                @csrf
                                <div class="form-group mb-0">
                                    <input type="email" class="form-control" placeholder="{{__('Your Email Address')}}" name="email" required>
                                </div>
                                <button type="submit" class="btn btn-base-1 btn-icon-left">
                                    {{__('Subscribe')}}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            {{-- <div class="col-lg-5 col-xl-4 text-center text-md-left">
                <div class="col">
                    <a href="{{ route('home') }}" class="d-block">
            @if($generalsetting->logo != null)
            <img loading="lazy" src="{{ asset($generalsetting->logo) }}" alt="{{ env('APP_NAME') }}" height="44">
            @else
            <img loading="lazy" src="{{ asset('frontend/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" height="44">
            @endif
            </a>
            <p class="mt-3">{{ $generalsetting->description }}</p>
            <div class="d-inline-block d-md-block">
                <form class="form-inline" method="POST" action="{{ route('subscribers.store') }}">
                    @csrf
                    <div class="form-group mb-0">
                        <input type="email" class="form-control" placeholder="{{__('Your Email Address')}}" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-base-1 btn-icon-left">
                        {{__('Subscribe')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-3 offset-xl-1 col-md-4">
        <div class="col text-center text-md-left">
            <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                {{__('Contact Info')}}
            </h4>
            <ul class="footer-links contact-widget">
                <li>
                    <span class="d-block opacity-5">{{__('Address')}}:</span>
                    <span class="d-block">{{ $generalsetting->address }}</span>
                </li>
                <li>
                    <span class="d-block opacity-5">{{__('Phone')}}:</span>
                    <span class="d-block">{{ $generalsetting->phone }}</span>
                </li>
                <li>
                    <span class="d-block opacity-5">{{__('Email')}}:</span>
                    <span class="d-block">
                        <a href="mailto:{{ $generalsetting->email }}">{{ $generalsetting->email  }}</a>
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="col text-center text-md-left">
            <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                {{__('Useful Link')}}
            </h4>
            <ul class="footer-links">
                @foreach (\App\Link::all() as $key => $link)
                <li>
                    <a href="{{ $link->url }}" title="">
                        {{ $link->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-md-4 col-lg-2">
        <div class="col text-center text-md-left">
            <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                {{__('My Account')}}
            </h4>

            <ul class="footer-links">
                @if (Auth::check())
                <li>
                    <a href="{{ route('logout') }}" title="Logout">
                        {{__('Logout')}}
                    </a>
                </li>
                @else
                <li>
                    <a href="{{ route('user.login') }}" title="Login">
                        {{__('Login')}}
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('purchase_history.index') }}" title="Order History">
                        {{__('Order History')}}
                    </a>
                </li>
                <li>
                    <a href="{{ route('wishlists.index') }}" title="My Wishlist">
                        {{__('My Wishlist')}}
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders.track') }}" title="Track Order">
                        {{__('Track Order')}}
                    </a>
                </li>
            </ul>
        </div>
        @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
        <div class="col text-center text-md-left">
            <div class="mt-4">
                <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                    {{__('Be a Seller')}}
                </h4>
                <a href="{{ route('shops.create') }}" class="btn btn-base-1 btn-icon-left">
                    {{__('Apply Now')}}
                </a>
            </div>
        </div>
        @endif
    </div> --}}
</div>
</div>
</div>

<div class="footer-bottom py-3 sct-color-3">
    <div class="container">
        <div class="row row-cols-xs-spaced flex flex-items-xs-middle">
            <div class="col-md-6">
                <div class="copyright text-center text-md-left">
                    <ul class="copy-links no-margin">
                        <li>
                            © {{ date('Y') }} {{ $generalsetting->site_name }}
                        </li>
                        <li>
                            <a href="{{ route('terms') }}">{{__('Terms')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('privacypolicy') }}">{{__('Privacy policy')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('sellerpolicy') }}">{{__('Seller policy')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('returnpolicy') }}">{{__('Return policy')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('supportpolicy') }}">{{__('Support policy')}}</a>
                        </li>                           
                        
                    </ul>
                </div>
            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-4">
                <div class="text-center text-md-right">
                    <ul class="inline-links">
                        @if (\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1)
                        <li>
                            <img loading="lazy" alt="paypal" src="{{ asset('frontend/images/icons/cards/paypal.png')}}" height="20">
                        </li>
                        @endif
                        @if (\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1)
                        <li>
                            <img loading="lazy" alt="stripe" src="{{ asset('frontend/images/icons/cards/stripe.png')}}" height="20">
                        </li>
                        @endif
                        @if (\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1)
                        <li>
                            <img loading="lazy" alt="sslcommerz" src="{{ asset('frontend/images/icons/cards/sslcommerz.png')}}" height="20">
                        </li>
                        @endif
                        @if (\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1)
                        <li>
                            <img loading="lazy" alt="instamojo" src="{{ asset('frontend/images/icons/cards/instamojo.png')}}" height="20">
                        </li>
                        @endif
                        @if (\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1)
                        <li>
                            <img loading="lazy" alt="razorpay" src="{{ asset('frontend/images/icons/cards/rozarpay.png')}}" height="20">
                        </li>
                        @endif
                        @if (\App\BusinessSetting::where('type', 'paystack')->first()->value == 1)
                        <li>
                            <img loading="lazy" alt="paystack" src="{{ asset('frontend/images/icons/cards/paystack.png')}}" height="20">
                        </li>
                        @endif
                        @if (\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1)
                        <li>
                            <img loading="lazy" alt="cash on delivery" src="{{ asset('frontend/images/icons/cards/cod.png')}}" height="20">
                        </li>
                        @endif
                        @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null && \App\Addon::where('unique_identifier', 'offline_payment')->first()->activated)
                        @foreach(\App\ManualPaymentMethod::all() as $method)
                        <li>
                            <img loading="lazy" alt="{{ $method->heading }}" src="{{ asset($method->photo)}}" height="20">
                        </li>
                        @endforeach
                        @endif
                        <li style="background: white;">
                            <img loading="lazy" alt="Khalti" src="https://dao578ztqooau.cloudfront.net/static/img/logo1.png" height="20">
                        </li>                            
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</footer>
