@extends('layouts.app')

@section('content')

<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Flash Deal Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('flash_deals.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Title')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Title')}}" id="name" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="background_color">{{__('Background Color')}} <small>(Hexa-code)</small></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('#FFFFFF')}}" id="background_color" name="background_color" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label" for="name">{{__('Text Color')}}</label>
                    <div class="col-lg-9">
                        <select name="text_color" id="text_color" class="form-control demo-select2" required>
                            <option value="">Select One</option>
                            <option value="white">{{__('White')}}</option>
                            <option value="dark">{{__('Dark')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="banner">{{__('Banner')}} <small>(1920x500)</small></label>
                    <div class="col-sm-9">
                        <input type="file" id="banner" name="banner" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="start_date">{{__('Date')}}</label>
                    <div class="col-sm-9">
                        {{-- <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control" name="start_date">
                                <span class="input-group-addon">{{__('to')}}</span>
                                <input type="text" class="form-control" name="end_date">
                            </div>
                        </div> --}}
                        <div id="demo-dp-range">

                            <input type="datetime-local" class="form-control" name="start_date">
                            <span class="input-group-addon">{{__('to')}}</span>
                            <input type="datetime-local" class="form-control" name="end_date">

                    </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="col-sm-3 control-label" for="categories">{{__('Categories')}}</label>
                    <div class="col-sm-4">
                        <select name="categories[]" id="categories" class="form-control demo-select2" multiple data-placeholder="Choose Categories">
                            @foreach(\App\Category::where('type','Category')->get() as $category)
                                <option value="{{$category->id}}">{{__($category->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                
                    <label class="col-sm-1 control-label" for="sellers">{{__('Sellers')}}</label>
                    <div class="col-sm-4">
                        <select name="sellers[]" id="sellers" class="form-control demo-select2" multiple data-placeholder="Choose Sellers">
                            @foreach(\App\Seller::with('user')->get() as $seller)
                                @isset($seller->user->name)
                                    <option value="{{$seller->user_id}}">{{__($seller->user->name)}}</option>
                                @endisset
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="col-sm-3 control-label" for="products">{{__('Products')}}</label>
                    <div class="col-sm-9">
                        <select name="products[]" id="products" class="form-control demo-select2" multiple required data-placeholder="Choose Products">
                            @foreach(\App\Product::where('published',1)->get() as $product)
                                <option value="{{$product->id}}">{{__($product->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="form-group" id="discount_table">

                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#products').on('change', function(){
                var product_ids = $('#products').val();
                if(product_ids.length > 0){
                    $.post('{{ route('flash_deals.product_discount') }}', {_token:'{{ csrf_token() }}', product_ids:product_ids}, function(data){
                        $('#discount_table').html(data);
                        $('.demo-select2').select2();
                    });
                }
                else{
                    $('#discount_table').html(null);
                }
            });

            $('#categories').on('change', function(){
                if($('#products').val() != ''){ 
                    $('#products').val(null).trigger('change');
                }
                if($('#sellers').val() != ''){ 
                    $('#sellers').val(null).trigger('change');
                }
                var category_ids = $('#categories').val();
                if(category_ids.length > 0){
                    $.post('{{ route('products.get_productids_by_category') }}', {_token:'{{ csrf_token() }}', category_ids:category_ids}, function(data){
                        var arr = [];
                        $.each(data, function(i, e){
                            arr.push(e.id);
                        })
                        $("#products").select2().val(arr).trigger('change');
                    });
                }
                else{
                    $('#discount_table').html(null);
                }
            });

            $('#sellers').on('change', function(){
                if($('#products').val() != ''){ 
                    $('#products').val(null).trigger('change');
                }
                if($('#categories').val() != ''){ 
                    $('#categories').val(null).trigger('change');
                }
                var seller_ids = $('#sellers').val();
                if(seller_ids.length > 0){
                    $.post('{{ route('products.get_productids_by_seller') }}', {_token:'{{ csrf_token() }}', seller_ids:seller_ids}, function(data){
                        var arr = [];
                        $.each(data, function(i, e){
                            arr.push(e.id);
                        })
                        $("#products").select2().val(arr).trigger('change');
                    });
                }
                else{
                    $('#discount_table').html(null);
                }
            });
        });
    </script>
@endsection
