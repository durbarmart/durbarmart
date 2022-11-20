@extends('layouts.app')

@section('content')

@php
    $parent_sub_sub_sub = 0;
    $parent_sub_sub = 0;
    $parent_sub = 0;
    $parent_cat = 0;
    if(isset($subsubsubsubcategory->parentSubSubSub) && !empty($subsubsubsubcategory->parentSubSub)){
        $parent_sub_sub_sub = $subsubsubsubcategory->parentSubSubSub->id;
    }
    if(isset($subsubsubsubcategory->parentSubSubSub->parentSubSub) && !empty($subsubsubsubcategory->parentSubSubSub->parentSubSub)){
        $parent_sub_sub = $subsubsubsubcategory->parentSubSubSub->parentSubSub->id;
    }
    if(isset($subsubsubsubcategory->parentSubSubSub->parentSubSub->parentSub) && !empty($subsubsubsubcategory->parentSubSubSub->parentSubSub->parentSub)){
        $parent_sub = $subsubsubsubcategory->parentSubSubSub->parentSubSub->parentSub->id;
    }
    if(isset($subsubsubsubcategory->parentSubSubSub->parentSubSub->parentSub->parentCat) && !empty($subsubsubsubcategory->parentSubSubSub->parentSubSub->parentSub->parentCat)){
        $parent_cat = $subsubsubsubcategory->parentSubSubSub->parentSubSub->parentSub->parentCat->id;
    }
    // dd($parent_sub_sub_sub,$parent_sub_sub,$parent_sub,$parent_cat);
@endphp
<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Sub Sub Sub Subcategory Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('subsubsubsubcategories.update', $subsubsubsubcategory->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
            @csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required value="{{$subsubsubsubcategory->name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Category')}}</label>
                    <div class="col-sm-9">
                        <select name="category_id" id="category_id" class="form-control demo-select2" required>
                            @foreach($categories as $category)
                                <option {{($parent_cat == $category->id)?'selected':''}} value="{{$category->id}}">{{__($category->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Subcategory')}}</label>
                    <div class="col-sm-9">
                        <select name="sub_category_id" id="sub_category_id" class="form-control demo-select2" required>

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('SubSubcategory')}}</label>
                    <div class="col-sm-9">
                        <select name="sub_sub_category_id" id="sub_sub_category_id" class="form-control demo-select2-placeholder" required>

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('SubSubSubcategory')}}</label>
                    <div class="col-sm-9">
                        <select name="sub_sub_sub_category_id" id="sub_sub_sub_category_id" class="form-control demo-select2-placeholder" required>

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{__('Meta Title')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="meta_title" value="{{ $subsubsubsubcategory->meta_title }}" placeholder="{{__('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{__('Description')}}</label>
                    <div class="col-sm-9">
                        <textarea name="meta_description" rows="8" class="form-control">{{ $subsubsubsubcategory->meta_description }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Slug')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Slug')}}" id="slug" name="slug" value="{{ $subsubsubsubcategory->slug }}" class="form-control">
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

@endsection


@section('script')

<script type="text/javascript">

    function get_subcategories_by_category(){
        var category_id = $('#category_id').val();
        $.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
            $('#sub_category_id').html(null);
            var subId = '{{$parent_sub}}';
            for (var i = 0; i < data.length; i++) {
                if(data[i].id == subId){
                    $('#sub_category_id').append($('<option>', {
                        selected:true,
                        value: data[i].id,
                        text: data[i].name
                    }));
                }else{
                    $('#sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $('.demo-select2').select2();
            }
        });
    }

    function get_subsubcategories_by_subcategory(){
        var sub_category_id = $('#sub_category_id').val();
        if(sub_category_id == '' || sub_category_id == null){
            var sub_category_id = '{{$parent_sub}}';
        }
        $.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', sub_category_id:sub_category_id}, function(data){
            $('#sub_sub_category_id').html(null);
            var subSubId = '{{$parent_sub_sub}}';
            for (var i = 0; i < data.length; i++) {
                if(data[i].id == subSubId){
                    $('#sub_sub_category_id').append($('<option>', {
                        selected:true,
                        value: data[i].id,
                        text: data[i].name
                    }));
                }else{
                    $('#sub_sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $('.demo-select2').select2();
            }
        });
    }
    
    function get_subsubsubcategories_by_subsubcategory(){
        var sub_sub_category_id = $('#sub_sub_category_id').val();
        
        if(sub_sub_category_id == '' || sub_sub_category_id == null){
            var sub_sub_category_id = '{{$parent_sub_sub}}';
        }
        $.post('{{ route('subsubsubcategories.get_subsubsubcategories_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', sub_sub_category_id:sub_sub_category_id}, function(data){
            
            $('#sub_sub_sub_category_id').html(null);
            var subSubSubId = '{{$parent_sub_sub_sub}}';
            for (var i = 0; i < data.length; i++) {                
                if(data[i].id == subSubSubId){
                    $('#sub_sub_sub_category_id').append($('<option>', {
                        selected:true,
                        value: data[i].id,
                        text: data[i].name
                    }));
                }else{
                    $('#sub_sub_sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $('.demo-select2').select2();
            }
        });
    }
    $('.demo-select2').select2();

    $(document).ready(function(){

        $("#category_id > option").each(function() {
            if(this.value == '{{$subsubsubsubcategory->parentSub->parent}}'){
                $("#category_id").val(this.value).change();
            }
        });

        get_subcategories_by_category();
        get_subsubcategories_by_subcategory();
        get_subsubsubcategories_by_subsubcategory();
    });

    $('#category_id').on('change', function() {
        get_subcategories_by_category();
    });
    $('#sub_category_id').on('change', function() {
        get_subsubcategories_by_subcategory();
    });
    $('#sub_sub_category_id').on('change', function() {
        get_subsubsubcategories_by_subsubcategory();
    });

</script>

@endsection
