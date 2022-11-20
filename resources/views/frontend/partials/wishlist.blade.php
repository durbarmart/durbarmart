<a href="{{ route('wishlists.index') }}" class="nav-box-link">
    {{-- <i class="fa fa-heart text-dark"></i> --}}
    <img data-toggle="tooltip" data-placement="top" title="Wishlist" src="{{asset('frontend/images/hort.svg')}}" alt="cart-logo" class="img-fluid img_sag">
    {{-- <span class="nav-box-text d-none d-lg-inline-block">{{__('Wishlist')}}</span> --}}
    @if(Auth::check())
        <sup class="nav-box-number">{{ count(Auth::user()->wishlists)}}</sup>
    @else
        <sup class="nav-box-number">0</sup>
    @endif
</a>
