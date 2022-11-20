<a href="{{ route('compare') }}" class="nav-box-link">
    {{-- <i class="fa fa-compress text-dark"></i> --}}
    <img data-toggle="tooltip" data-placement="top" title="Compare" src="{{asset('frontend/images/coma.svg')}}" alt="cart-logo" class="img-fluid img_sag">
    {{-- <span class="nav-box-text d-none d-lg-inline-block">{{__('Compare')}}</span> --}}
    @if(Session::has('compare'))
        <sup class="nav-box-number">{{ count(Session::get('compare'))}}</sup>
    @else
        <sup class="nav-box-number">0</sup>
    @endif
</a>
