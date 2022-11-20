@extends('layouts.app')

@section('content')
<style>
    table{
        border-collapse: collapse;
        background: white;
        table-layout: fixed;
        width: 100%;
    }
    .panel-body{
        width: 100%;
        overflow-x: scroll;
    }
    
    th, td {
    padding: 8px 16px;
    width: 160px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <a href="{{ route('truncate',['table' => 'Category'])}}" class="btn btn-rounded btn-danger pull-left">{{__('Empty Categories')}}</a>
    </div>
    <div class="col-sm-6">
        <a href="{{ route('categories.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Category')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Categories')}}</h3>
        <div class="pull-right clearfix" style="display: flex;">
                
            <div class="box-inline pad-rgt pull-left">
                <div class="">
                    <button class="btn btn-primary" id="bulkDelBtn" onclick="deleteBulkData();">Delete</button>
                </div>
            </div>
            <form class="" id="sort_categories" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type name & Enter">
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 75px;display:flex;">
                        Show
                        <select name="items_per_page" id="items_per_page" class="form-control">
                            <option value="100" {{(isset($_GET['items_per_page']) && $_GET['items_per_page'] == 100)?'selected':''}}>100</option>
                            <option value="500" {{(isset($_GET['items_per_page']) && $_GET['items_per_page'] == 500)?'selected':''}}>500</option>
                            <option value="1000" {{(isset($_GET['items_per_page']) && $_GET['items_per_page'] == 1000)?'selected':''}}>1000</option>
                        </select>
                        items
                        {{-- <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type name & Enter"> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Type')}}</th>
                    <th>{{__('Slug')}}</th>
                    <th>{{__('Meta Title')}}</th>
                    <th>{{__('Meta Description')}}</th>
                    <th>{{__('Featured')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                    <tr>
                        <td>
                            <input type="checkbox" value="{{ $category->id }}" data-id="{{ $category->id }}"
                            name="categoryId[]" class="rowCheck">
                        </td>
                        <td>{{ ($key+1) + ($categories->currentPage() - 1)*$categories->perPage() }}</td>
                        {{-- <td><a href="{{route('products.category',$category->slug)}}" target="_blank">{{__($category->name)}}</a></td> --}}
                        <td><a href="{{route('products.collectionSlug',$category->slug)}}" target="_blank">{{__($category->name)}}</a></td>
                        
                        <td>{{__($category->type)}}</td>
                        <td>{{__($category->slug)}}</td>
                        <td>{{__($category->meta_title)}}</td>

                        <td>{{__($category->meta_description)}}</td>
                        <td><label class="switch">
                            <input onchange="update_featured(this)" value="{{ $category->id }}" type="checkbox" <?php if($category->featured == 1) echo "checked";?> >
                            <span class="slider round"></span></label></td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    @if ($category->type == 'Category')
                                        <li><a href="{{route('categories.edit', encrypt($category->id))}}">{{__('Edit')}}</a></li>
                                    @elseif($category->type == 'SubCategory')
                                        <li><a href="{{route('subcategories.edit', encrypt($category->id))}}">{{__('Edit')}}</a></li>
                                    @elseif($category->type == 'SubSubCategory')
                                        <li><a href="{{route('subsubcategories.edit', encrypt($category->id))}}">{{__('Edit')}}</a></li>
                                    @elseif($category->type == 'SubSubSubCategory')
                                        <li><a href="{{route('subsubsubcategories.edit', encrypt($category->id))}}">{{__('Edit')}}</a></li>
                                    @elseif($category->type == 'SubSubSubSubCategory')
                                        <li><a href="{{route('subsubsubsubcategories.edit', encrypt($category->id))}}">{{__('Edit')}}</a></li>
                                    @elseif($category->type == 'SubSubSubSubSubCategory')
                                        <li><a href="javascript:void(0);" class="disabled-feature">{{__('Edit')}}</a></li>
                                    @elseif($category->type == 'SubSubSubSubSubSubCategory')
                                        <li><a href="javascript:void(0);" class="disabled-feature">{{__('Edit')}}</a></li>
                                    @else
                                        
                                    @endif
                                    <li><a onclick="confirm_modal('{{route('categories.destroy', $category->id)}}');">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $categories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        $("#checkAll").click(function() {
            $(".rowCheck").prop('checked', $(this).prop('checked'));
        });
        function deleteBulkData() {
            var allIds = [];
            $(".rowCheck:checked").each(function() {
                allIds.push($(this).val());
            });
            if (allIds.length <= 0) {
                alert("Please select row.");
            } else {
                var check = confirm("Are you sure you want to perform bulk delete?");
                if (check == true) {
                    var join_checked_values = allIds.join(",");
                    $.ajax({
                        url: "{{ route('category.bulkDelete') }}",
                        type: 'get',
                        data: {
                            'ids': join_checked_values
                        },
                        beforeSend: function()
                        {
                            $(".myoverlay").css('display', 'block');
                        },
                        success: function(data) {
                            if (data['success']) {
                                $(".rowCheck:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });
                                $(".myoverlay").css('display', 'none');
                                alert(data['success']);
                                location.href = data.redirectTo;
                            } else if (data['error']) {
                                $(".myoverlay").css('display', 'none');
                                alert(data['error']);
                            } else {
                                $(".myoverlay").css('display', 'none');
                                alert('Whoops something went wrong');
                            }
                        },
                        error: function(data) {
                            alert(data.responseText);
                        }
                    });
                    // $.each(allIds, function(index, value) {
                    //     $('table tr').filter("[data-row-id='" + value + "']").remove();
                    // });
                }
            }
        }
    
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
        return null;
        }
        return decodeURI(results[1]) || 0;
    }
    $("#items_per_page").on('change',function(){
        var val = $(this).val();
        var current = window.location.origin;
        var appendUrl = '';
        // if($.urlParam('search') == ""){
        // appendUrl += '/admin/all-categories?search='+$.urlParam('search');
        // }else{
        //     appendUrl = '='+val;
        // }
        // if($.urlParam('items_per_page') == ""){
        //     appendUrl = '?items_per_page='+val;
        // }else{
            appendUrl = '&items_per_page='+val;
        // }
        window.location.replace(current+'/admin/all-categories?search='+($.urlParam('search')??'')+appendUrl);
        // console.log($.urlParam('items_per_page'));
        // console.log(val);
    });
        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('categories.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Featured categories updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
