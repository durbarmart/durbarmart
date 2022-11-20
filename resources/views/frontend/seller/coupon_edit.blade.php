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
                                        {{__('Update your coupon')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li><a href="{{ route('seller.coupons') }}">{{__('Coupons')}}</a></li>
                                            <li class="active"><a href="{{ route('seller.coupon.edit', $coupon->id) }}">{{__('Edit Coupon')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="form-horizontal" action="{{ route('seller.coupon.update', $coupon->id) }}" method="POST">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <input name="_method" type="hidden" value="PATCH">
                                <div class="form-box-content p-3">
                                    <input type="hidden" name="id" value="{{ $coupon->id }}" id="id">
                                    <div class="row">
                                        <label class="col-md-2 control-label" for="name">{{__('Coupon Type')}}</label>
                                        <div class="col-md-10">
                                            <select name="coupon_type" id="coupon_type" class="form-control demo-select2-placeholder" onchange="coupon_form()" required>
                                                @if ($coupon->type == "product_base")
                                                    <option value="product_base" selected>{{__('For Products')}}</option>
                                                @elseif ($coupon->type == "cart_base")
                                                    <option value="cart_base">{{__('For Total Orders')}}</option>
                                                @endif
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
    <script type="text/javascript">

        function coupon_form(){
            var coupon_type = $('#coupon_type').val();
            var id = $('#id').val();
            $.post('{{ route('seller.coupon.get_coupon_form_edit') }}',{_token:'{{ csrf_token() }}', coupon_type:coupon_type, id:id}, function(data){
                $('#coupon_form').html(data);

                $('#demo-dp-range .input-daterange').datepicker({
                    startDate: '-0d',
                    todayBtn: "linked",
                    autoclose: true,
                    todayHighlight: true
                });
            });
        }

        $(document).ready(function(){
            coupon_form();
        });


    </script>
@endsection
