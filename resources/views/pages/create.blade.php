@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel">
            <div class="panel-heading bord-btm">
                <h3 class="panel-title">{{__('Create Custom Page')}}</h3>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="title">{{__('Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Title')}}" id="title" name="title" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="slug">{{__('Slug')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('your-slug')}}" id="slug" name="slug" class="form-control" required>
                            <small><code>http://domain.com/your-slug</code> Only a-z, numbers, hypen allowed</small>
                        </div>
                    </div>
					<div class="form-group" id="category">
						<label class="col-sm-3 control-label" for="category">{{__('Category')}}</label>
						<div class="col-sm-9">
							<select class="form-control demo-select2-placeholder" name="category_id" id="category_id">
                                <option value=""></option>
								@foreach($categories as $category)
									<option value="{{$category->id}}">{{__($category->name)}}</option>
								@endforeach
							</select>
						</div>
					</div>
                    <div class="form-group" id="product">
						<label class="col-sm-3 control-label" for="product">{{__('Product')}}</label>
						<div class="col-sm-9">
							<select class="form-control demo-select2-placeholder" name="product_id[]" id="product_id" multiple="multiple">

							</select>
						</div>
					</div>
                    <div class="form-group">
						<label class="col-sm-3 control-label" for="brand">{{__('Brand')}}</label>
						<div class="col-sm-9">
							<select class="form-control demo-select2-placeholder" name="brand_id">
								<option value=""></option>
								@foreach (\App\Brand::all() as $brand)
									<option value="{{ $brand->id }}">{{ $brand->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
                    <div class="form-group">
						<label class="col-sm-3 control-label" for="seller">{{__('Seller')}}</label>
						<div class="col-sm-9">
                            <select class="form-control demo-select2-placeholder" name="seller_id" id="seller_id">
                                <option value=""></option>
                                @foreach(\App\Seller::with('user')->get() as $seller)
                                    @isset($seller->user->name)
                                        <option value="{{$seller->user_id}}">{{__($seller->user->name)}}</option>
                                    @endisset
                                @endforeach
							</select>
						</div>
					</div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="content">{{__('Content')}}</label>
                        <div class="col-sm-9">
                            <textarea class="editor" name="content" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="slug">{{__('Meta Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Meta Title')}}" id="meta_title" name="meta_title" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="meta_description">{{__('Meta Description')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Meta Description')}}" id="meta_description" name="meta_description" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="keywords">{{__('Keywords')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Keywords')}}" id="keywords" name="keywords" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="meta_image">{{__('Meta Image')}} <small>(200x300)</small></label>
                        <div class="col-sm-9">
                            <input type="file" id="meta_image" name="meta_image" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{__('Add New')}}</button>
                </div>
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
     
    $('#category_id').on('change', function() {
	    var category_id = $('#category_id').val();
		$.post('{{ route('products.get_products_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
		    $('#product_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#product_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		});
	});

    </script>
@endsection