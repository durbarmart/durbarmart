@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Category Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required value="{{$category->name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Type')}}</label>
                    <div class="col-sm-10">
                        <select name="digital" required class="form-control demo-select2-placeholder">
                            <option value="0" @if ($category->digital == '0') selected @endif>{{__('Physical')}}</option>
                            <option value="1" @if ($category->digital == '1') selected @endif>{{__('Digital')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="banner">{{__('Banner')}} <small>(200x300)</small></label>
                    <div class="col-sm-10">
                        <input type="file" id="banner" name="banner" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="icon">{{__('Icon')}} <small>(32x32)</small></label>
                    <div class="col-sm-10">
                        <input type="file" id="icon" name="icon" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Meta Title')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="meta_title" value="{{ $category->meta_title }}" placeholder="{{__('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Description')}}</label>
                    <div class="col-sm-10">
                        <textarea name="meta_description" rows="8" class="form-control">{{ $category->meta_description }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Slug')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Slug')}}" id="slug" name="slug" value="{{ $category->slug }}" class="form-control">
                    </div>
                </div>
                @if (\App\BusinessSetting::where('type', 'category_wise_commission')->first()->value == 1)
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="name">{{__('Commission Rate')}}</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" step="0.01" id="commision_rate" name="commision_rate" value="{{ $category->commision_rate }}" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <option class="form-control">%</option>
                        </div>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="name">{{__('Brand Category')}}</label>
                <div class="col-sm-9">
                    <input {{ ($category->type == 'BrandCategory')?'checked':'' }} type="checkbox" name="brand_category" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="name">{{__('Linked Category')}}</label>
                <div class="col-sm-9">
                    <select name="linked_category" required class="form-control demo-select2-placeholder">
                        <option disabled selected>Select Linked Category</option>
                        @foreach (\App\Category::where('type','Category')->get() as $item)
                            <option {{ ($category->parent == $item->id)?'selected':'' }} value="{{$item->id}}">{{$item->name}}</option>                                
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="name">{{__('Linked Brand')}}</label>
                <div class="col-sm-9">
                    <select name="linked_brand" required class="form-control demo-select2-placeholder">
                        <option disabled selected>Select Linked Brand</option>
                        @foreach (\App\Brand::all() as $item)
                            <option {{ ($category->brand_id == $item->id)?'selected':'' }} value="{{$item->id}}">{{$item->name}}</option>                                
                        @endforeach
                    </select>
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
