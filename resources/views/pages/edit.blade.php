@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-8 col-lg-offset-2">
        <div class="panel">
            <div class="panel-heading bord-btm">
                <h3 class="panel-title">{{__('Edit Page')}}</h3>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="title">{{__('Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Title')}}" id="title" name="title" value="{{ $page->title }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="slug">{{__('Slug')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Slug')}}" id="slug" name="slug" value="{{ $page->slug }}" class="form-control" required>
                            <small><code>http://domain.com/your-slug</code> Only a-z, numbers, hypen allowed</small>
                        </div>
                    </div>
                    <div class="form-group" id="category">
						<label class="col-sm-3 control-label" for="category">{{__('Category')}}</label>
						<div class="col-sm-9">
							<select class="form-control demo-select2-placeholder" name="category_id" id="category_id">
                                <option value=""></option>
								@foreach($categories as $category)
									<option value="{{$category->id}}" <?php if($page->category_id == $category->id) echo "selected"; ?>>{{__($category->name)}}</option>
								@endforeach
							</select>
						</div>
					</div>
                    <div class="form-group" id="product">
						<label class="col-sm-3 control-label" for="product">{{__('Product')}}</label>
						<div class="col-sm-9">
                            <?php 
                                $pages=\App\Page::where('id',$page->id)->first();
                                $array = json_decode($pages->product_id); 
                                $products=\App\Product::where('category_id',$pages->category_id)->get();
                                
                                
                            ?>
							<select class="form-control demo-select2-placeholder" name="product_id[]" id="product_id" multiple="multiple">
                                @foreach ($products as $product)
                                    <option value="{{$product->id}}"  <?php if(in_array($product->id,$array)) echo 'selected' ?>>{{$product->name}}</option>
                                @endforeach
							</select>
						</div>
					</div>
                    <div class="form-group">
						<label class="col-sm-3 control-label" for="brand">{{__('Brand')}}</label>
						<div class="col-sm-9">
							<select class="form-control demo-select2-placeholder" name="brand_id">
								<option value=""></option>
								@foreach (\App\Brand::all() as $brand)
									<option value="{{ $brand->id }}" <?php if($page->brand_id == $brand->id) echo "selected"; ?>>{{ $brand->name }}</option>
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
                                        <option value="{{$seller->user_id}}" <?php if($page->seller_id == $seller->id) echo "selected"; ?>>{{__($seller->user->name)}}</option>
                                    @endisset
                                @endforeach
							</select>
						</div>
					</div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="content">{{__('Content')}}</label>
                        <div class="col-sm-9">
                            <textarea class="editor" name="content" required>
                                @php
                                    echo $page->content
                                @endphp
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="slug">{{__('Meta Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Meta Title')}}" id="meta_title" name="meta_title" value="{{ $page->meta_title }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="meta_description">{{__('Meta Description')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Meta Description')}}" id="meta_description" name="meta_description" value="{{ $page->meta_description }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="keywords">{{__('Keywords')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Keywords')}}" id="keywords" name="keywords" value="{{ $page->keywords }}" class="form-control">
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
                    <button class="btn btn-purple" type="submit">{{__('Update')}}</button>
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
                // $option = $('<option></option>').val(data[i].id).html(data[i].name); //first initialize variable
		        $('#product_id').append($('<option>', {
                    // if(data[i].id == '157') {
                    //     $option = $option.attr('selected','selected'),
                    //     $("#product_id").append($option)
                    // }
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		});
	});

    </script>
@endsection