@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Category Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Type')}}</label>
                    <div class="col-sm-9">
                        <select name="digital" required class="form-control demo-select2-placeholder">
                            <option value="0">{{__('Physical')}}</option>
                            <option value="1">{{__('Digital')}}</option>
                            <option value="1">{{__('Brand Category')}}</option>
                        </select>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="banner">{{__('Banner')}} <small>(200x300)</small></label>
                    <div class="col-sm-9">
                        <input type="file" id="banner" name="banner" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="icon">{{__('Icon')}} <small>(32x32)</small></label>
                    <div class="col-sm-9">
                        <input type="file" id="icon" name="icon" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{__('Meta Title')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="meta_title" placeholder="{{__('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{__('Description')}}</label>
                    <div class="col-sm-9">
                        <textarea name="meta_description" rows="8" class="form-control"></textarea>
                    </div>
                </div>
                @if (\App\BusinessSetting::where('type', 'category_wise_commission')->first()->value == 1)
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{__('Commission Rate')}}</label>
                        <div class="col-sm-7">
                            <input type="number" min="0" step="0.01" placeholder="{{__('Commission Rate')}}" id="commision_rate" name="commision_rate" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <option class="form-control">%</option>
                        </div>
                @endif
            </div>
                
            <div class="form-group">
                <label class="col-sm-3 control-label" for="name">{{__('Brand Category')}}</label>
                <div class="col-sm-9">
                    <input type="checkbox" name="brand_category" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="name">{{__('Linked Category')}}</label>
                <div class="col-sm-9">
                    <select name="linked_category" required class="form-control demo-select2-placeholder">
                        <option disabled selected>Select Linked Category</option>
                        @foreach (\App\Category::where('type','Category')->get() as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>                                
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
                            <option value="{{$item->id}}">{{$item->name}}</option>                                
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
