@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Add Your Coupon')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li><a href="{{ route('seller.coupons') }}">{{__('Coupons')}}</a></li>
                                            <li class="active"><a href="{{ route('seller.coupon.upload') }}">{{__('Add New Coupon')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="" action="{{ route('seller.coupon.store') }}" method="POST">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <label class="col-md-2 control-label" for="name">{{__('Coupon Type')}}</label>
                                        <div class="col-md-10">
                                            <select name="coupon_type" id="coupon_type" class="form-control demo-select2" onchange="coupon_form()" required>
                                                <option value="">Select One</option>
                                                <option value="product_base">{{__('For Products')}}</option>
                                                <option value="cart_base">{{__('For Total Orders')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4" id="coupon_form">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')

{{-- <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script> --}}

<script type="text/javascript">
    $(document).ready(function() {
        $('.demo-select2').select2();
    });


    function coupon_form(){
        var coupon_type = $('#coupon_type').val();
        // var coupon_type = 'cart_base';
		$.post('{{ route('seller.coupon.get_coupon_form') }}',{_token:'{{ csrf_token() }}', coupon_type:coupon_type}, function(data){
            $('#coupon_form').html(data);

            $('#frontend-demo-dp-range .input-daterange').datepicker({
                startDate: '-0d',
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true
            });
		});
    }
</script>
@endsection
