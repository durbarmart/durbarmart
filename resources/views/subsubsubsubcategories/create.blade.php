@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Sub Sub Sub Subcategory Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('subsubsubsubcategories.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Category')}}</label>
                    <div class="col-sm-9">
                        <select name="category_id" id="category_id" class="form-control demo-select2-placeholder" required>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{__($category->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Subcategory')}}</label>
                    <div class="col-sm-9">
                        <select name="sub_category_id" id="sub_category_id" class="form-control demo-select2-placeholder" required>

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
                        <input type="text" class="form-control" name="meta_title" placeholder="{{__('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{__('Description')}}</label>
                    <div class="col-sm-9">
                        <textarea name="meta_description" rows="8" class="form-control"></textarea>
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
            for (var i = 0; i < data.length; i++) {
                $('#sub_category_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
                $('.demo-select2').select2();
            }
        });
    }

    function get_subsubcategories_by_subcategory(){
        var sub_category_id = $('#sub_category_id').val();
        $.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', sub_category_id:sub_category_id}, function(data){
            $('#sub_sub_category_id').html(null);
            for (var i = 0; i < data.length; i++) {
                $('#sub_sub_category_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
                $('.demo-select2').select2();
            }
        });
    }
    
    function get_subsubsubcategories_by_subsubcategory(){
        var sub_sub_category_id = $('#sub_sub_category_id').val();
        $.post('{{ route('subsubsubcategories.get_subsubsubcategories_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', sub_sub_category_id:sub_sub_category_id}, function(data){
            
        console.log(data);
            $('#sub_sub_sub_category_id').html(null);
            for (var i = 0; i < data.length; i++) {
                $('#sub_sub_sub_category_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
                $('.demo-select2').select2();
            }
        });
    }
    $(document).ready(function(){
        get_subcategories_by_category();
        get_subsubcategories_by_subcategory();
        // get_subsubsubcategories_by_subsubcategory();
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
