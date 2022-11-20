@extends('layouts.app')
@section('content')
<div class="row">
<div class="col-lg-6">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Brand Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required value="{{ $brand->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="logo">{{__('Logo')}} <small>(120x80)</small></label>
                    <div class="col-sm-10">
                        <input type="file" id="logo" name="logo" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Meta Title')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="meta_title" value="{{ $brand->meta_title }}" placeholder="{{__('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Description')}}</label>
                    <div class="col-sm-10">
                        <textarea name="meta_description" rows="8" class="form-control">{{ $brand->meta_description }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Slug')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Slug')}}" id="slug" name="slug" value="{{ $brand->slug }}" class="form-control">
                    </div>
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
<div class="col-lg-6">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('SEO')}}</h3>
        </div>
        <p style="padding:0 0 15px 15px;">
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Category
            </a>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">
            Sub Category
            </button>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample">
            Sub Sub Category
            </button>
        </p>
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <form class="form-horizontal" action="{{ route('updateSEO', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="category">
                    <div class="panel-body">
                        @foreach (\App\Models\Category::get() as $a => $b)
                        @php
                            $old = \App\BrandSeo::where(['brand_id' => $brand->id,'type' => 'category','type_id' => $b->id])->first();
                        @endphp
                        <div class="form-group">
                            <label style="text-align: left;" class="col-sm-12 control-label" for="name">{{$brand->slug.'/'.$b->slug}} 
                                <a target="_blank" href="{{ route('brands.cateogryGet',['slug' => $brand->slug,'categorySlug' => $b->slug])}}"><i class="fa fa-arrow-right"></i></a> 
                            </label>
                            <div class="col-sm-12">
                                <input type="text" placeholder="{{__('SEO Title')}}" name="items[{{$b->id}}][title]" class="form-control" value="{{(isset($old->seo_title))?$old->seo_title:''}}">
            
                                <input type="text" placeholder="{{__('SEO Description')}}" name="items[{{$b->id}}][description]" class="form-control mt-2" value="{{(isset($old->seo_description))?$old->seo_description:''}}">
                            </div>
                        </div>
                            
                        @endforeach
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="collapse" id="collapseExample2">
            <div class="card card-body">
                
                <form class="form-horizontal" action="{{ route('updateSEO', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="subcategory">
                    <div class="panel-body">
                        @foreach (\App\Models\SubCategory::get() as $a => $b)
                        @php
                            $old = \App\BrandSeo::where(['brand_id' => $brand->id,'type' => 'subcategory','type_id' => $b->id])->first();
                        @endphp
                        <div class="form-group">
                            <label style="text-align: left;" class="col-sm-12 control-label" for="name">{{$brand->slug.'/'.$b->slug}}
                                <a target="_blank" href="{{ route('brands.cateogryGet',['slug' => $brand->slug,'categorySlug' => $b->slug])}}"><i class="fa fa-arrow-right"></i></a> 
                            </label>
                            <div class="col-sm-12">
                                <input type="text" placeholder="{{__('SEO Title')}}" name="items[{{$b->id}}][title]" class="form-control" value="{{(isset($old->seo_title))?$old->seo_title:''}}">

                                <input type="text" placeholder="{{__('SEO Description')}}" name="items[{{$b->id}}][description]" class="form-control mt-2" value="{{(isset($old->seo_description))?$old->seo_description:''}}">
                            </div>
                        </div>
                            
                        @endforeach
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="collapse" id="collapseExample3">
            <div class="card card-body">
                
                <form class="form-horizontal" action="{{ route('updateSEO', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="subsubcategory">
                    <div class="panel-body">
                        @foreach (\App\Models\SubSubCategory::get() as $a => $b)
                        @php
                            $old = \App\BrandSeo::where(['brand_id' => $brand->id,'type' => 'subsubcategory','type_id' => $b->id])->first();
                        @endphp
                        <div class="form-group">
                            <label style="text-align: left;" class="col-sm-12 control-label" for="name">{{$brand->slug.'/'.$b->slug}}
                                <a target="_blank" href="{{ route('brands.cateogryGet',['slug' => $brand->slug,'categorySlug' => $b->slug])}}"><i class="fa fa-arrow-right"></i></a> 
                            </label>
                            <div class="col-sm-12">
                                <input type="text" placeholder="{{__('SEO Title')}}" name="items[{{$b->id}}][title]" class="form-control" value="{{(isset($old->seo_title))?$old->seo_title:''}}">

                                <input type="text" placeholder="{{__('SEO Description')}}" name="items[{{$b->id}}][description]" class="form-control mt-2" value="{{(isset($old->seo_description))?$old->seo_description:''}}">
                            </div>
                        </div>
                            
                        @endforeach
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
</div>
@endsection
