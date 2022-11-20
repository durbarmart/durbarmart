@extends('layouts.app')

@section('content')

    {{-- @if ($type != 'Seller') --}}
        <div class="row">
            <div class="col-lg-12 pull-right">
                <a href="{{ route('products.create') }}"
                    class="btn btn-rounded btn-info pull-right">{{ __('Add New Product') }}</a>
            </div>
        </div>
    {{-- @endif --}}

<br>
<div class="panel">
    <!--Panel heading-->

    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{ __($type.' Products') }}</h3>
        <div class="pull-right clearfix d-flex align-items-center">
            <form class="" id="sort_products" action="" method="GET">
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
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"
                            @isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="Type & Enter">
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2" style="margin: 0px 10px;">
                Bulk Discount
            </button>
            @if ($type == 'Seller')
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin: 0px 10px;">
                Filter
            </button>
            @endif

        
            <button class="btn btn-primary" id="bulkDelBtn" onclick="deleteBulkData();">Delete</button>
        </div>
    </div>      
    <div class="panel-body">
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%" id="productTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>#</th>
                    <th width="20%">{{ __('Name') }}</th>
                    @if ($type == 'Seller')
                        <th>{{ __('Seller Name') }}</th>
                    @endif
                    <th>{{ __('Num of Sale') }}</th>
                    <th>{{ __('Total Stock') }}</th>
                    <th>{{ __('Base Price') }}</th>
                    {{-- <th>{{ __('Todays Deal') }}</th> --}}
                    <th>{{ __('Rating') }}</th>
                    <th>{{ __('Published') }}</th>
                    <th>{{ __('Featured') }}</th>
                    <th>{{ __('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td>
                            <input type="checkbox" value="{{ $product->id }}" data-id="{{ $product->id }}"
                            name="productID[]" class="rowCheck">
                        </td>
                        <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                        <td>
                            <a href="{{ route('product', $product->slug) }}" target="_blank" class="media-block">
                                <div class="media-left">
                                    @if ($product->photos != null)
                                        <img loading="lazy"  class="img-md" src="{{ asset(isset(json_decode($product->photos)[0]) ? json_decode($product->photos)[0] : 'img/nothing-found.jpg')}}" alt="Image">
                                    @endif 
                                </div>
                                <div class="media-body">{{ __($product->name) }}</div>
                            </a>
                        </td>
                        @if($type == 'Seller')
                            <td>
                                @if (App\User::where('id',$product->user_id)->count() > 0)
                                @php
                                    $user = App\User::where('id',$product->user_id)->first();
                                @endphp
                                {{ $user->name }}
                                @else
                                    User Not Found
                                @endif
                            
                            </td>
                        @endif
                        <td>{{ $product->num_of_sale }} {{ __('times') }}</td>
                        @php
                            if ($product->variant_product) {
                                $table_data_html = '<td>';
                            } else {
                                $table_data_html = '<td class="updateData" data-name="qty" data-type="text" data-pk="' . $product->id . '" data-title="Enter stock">';
                            }
                        @endphp
                        @php
                            echo $table_data_html;
                        @endphp
                        {{-- <td class="updateData" data-name="qty" data-type="text" data-pk="{{ $product->id }}" data-title="Enter stock"> --}}
                        @php
                            $qty = 0;
                            if ($product->variant_product) {
                                foreach ($product->stocks as $key => $stock) {
                                    $qty += $stock->qty;
                                }
                            } else {
                                $qty = $product->current_stock;
                            }
                            echo $qty;
                        @endphp
                        </td>
                        <td class="updateData" data-name="price" data-type="text" data-pk="{{ $product->id }}"
                            data-title="Enter Product Price">{{ number_format($product->unit_price, 2) }}</td>
                        {{-- <td><label class="switch">
                                <input onchange="update_todays_deal(this)" value="{{ $product->id }}" type="checkbox" @if ($product->todays_deal == 1) {{'checked'}} @endif>
                                <span class="slider round"></span></label></td> --}}
                        <td>{{ $product->rating }}</td>
                        <td><label class="switch">
                                <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox"
                                    <?php if ($product->published == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span></label></td>
                        <td><label class="switch">
                                <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox"
                                    <?php if ($product->featured == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span></label></td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                    data-toggle="dropdown" type="button">
                                    {{ __('Actions') }} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    @if ($type == 'Seller')
                                        <li><a
                                                href="{{ route('products.seller.edit', encrypt($product->id)) }}">{{ __('Edit') }}</a>
                                        </li>
                                    @else
                                        <li><a
                                                href="{{ route('products.admin.edit', encrypt($product->id)) }}">{{ __('Edit') }}</a>
                                        </li>
                                    @endif
                                    <li><a
                                            onclick="confirm_modal('{{ route('products.destroy', $product->id) }}');">{{ __('Delete') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ route('products.duplicate', $product->id) }}">{{ __('Duplicate') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $products->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Bulk Discount</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form class="" id="discount_products" action="{{route('bulk.discount')}}" method="POST">
                    {{-- @if($type == 'Seller') --}}
                    @csrf
                    <div class="card" style="margin-top:15px">
                        <div class="card-header">
                          Sellers
                        </div>
                        <select class="form-control sortSelect demo-select2" data-placeholder="{{__('All Sellers')}}" name="seller">
                            <option value=""></option>
                            @foreach (\App\Seller::all() as $key => $seller)
                                @if ($seller->user != null && $seller->user->shop != null)
                                    <option value="{{ $seller->user->id }}" >{{ $seller->user->shop->name }}</option>
                                @endif
                            @endforeach
                        </select>                        
                    </div>
                    {{-- @endif --}}

                    @if ($type=='Seller')
                        
                    
                    <div class="card" style="margin-top:15px">
                        <div class="card-header">
                            Discount
                        </div>
						<div class="col-lg-7">
							<input type="number" min="0" value="0" step="0.01" placeholder="{{__('Discount')}}" name="discount" class="form-control" required>
						</div>
						<div class="col-lg-4">
							<select class="demo-select2" name="discount_type">
								<option value="amount">{{__('Flat')}}</option>
								<option value="percent">{{__('Percent')}}</option>
							</select>
						</div>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-success" style="margin-top:10px">Apply</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter By</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form class="" id="sort_products" action="" method="GET">
                        <div class="card">
                            
                            @if($type == 'Seller')
                                {{-- <div class="box-inline pad-rgt pull-left">
                                    <div class="select" style="min-width: 200px;"> --}}
                                    <div class="card">
                                        <div class="card-header">
                                            
                                        </div>
                                        <select class="form-control demo-select2" name="type" id="type" onchange="sort_products()">
                                            <option value="">Sort by</option>
                                            <option value="rating,desc" @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'desc') selected @endif
                                                @endisset>{{ __('Rating (High > Low)') }}</option>
                                            <option value="rating,asc" @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'asc') selected @endif
                                                @endisset>{{ __('Rating (Low > High)') }}</option>
                                            <option value="num_of_sale,desc" @isset($col_name, $query)
                                                @if ($col_name == 'num_of_sale' && $query == 'desc') selected @endif @endisset>{{ __('Num of Sale (High > Low)') }}</option>
                                            <option value="num_of_sale,asc" @isset($col_name, $query)
                                                @if ($col_name == 'num_of_sale' && $query == 'asc') selected @endif @endisset>{{ __('Num of Sale (Low > High)') }}</option>
                                            <option value="unit_price,desc" @isset($col_name, $query)
                                                @if ($col_name == 'unit_price' && $query == 'desc') selected @endif @endisset>{{ __('Base Price (High > Low)') }}</option>
                                            <option value="unit_price,asc" @isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'asc') selected @endif
                                                @endisset>{{ __('Base Price (Low > High)') }}</option>
                                        </select>
                                    </div>
                                    {{-- </div>
                                </div> --}}
                            @endif
                        </div>
                        @if($type == 'Seller')
                        <div class="card" style="margin-top:15px">
                            <div class="card-header">
                              Sellers
                            </div>


                            {{-- <div class="box-inline pad-rgt pull-left">
                                <div class="select" style="min-width: 200px;"> --}}
                                    <select class="form-control sortSelect demo-select2" data-placeholder="{{__('All Sellers')}}" name="seller" onchange="sort_products()">
                                        <option value=""></option>
                                        @foreach (\App\Seller::all() as $key => $seller)
                                            @if ($seller->user != null && $seller->user->shop != null)
                                                <option value="{{ $seller->user->id }}" >{{ $seller->user->shop->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                {{-- </div>
                            </div> --}}
                            
                        </div>
                        @endif

                        @if ($type=='Seller')
                            
                        
                        <div class="card" style="margin-top:15px">
                            <div class="card-header">
                                Status
                            </div>
                            <select class="form-control sortSelect demo-select2" data-placeholder="{{__('Status')}}" name="status" onchange="sort_products()">
                                <option></option>
                                <option value="1" @if ($status==1) selected @endif>Published</option>
                                <option value="0" @if ($status==0) selected @endif>UnPublished</option>
                            </select>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-success" style="margin-top:10px">Filter Products</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });
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
            var appendUrl = '&items_per_page='+val;
            window.location.replace(current+'/admin/products/seller?search='+($.urlParam('search')??'')+appendUrl);
        });
        // function filter() {
        //     $('#search-form').submit();
        // }
        function update_todays_deal(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    showAlert('success', 'Todays Deal updated successfully');
                } else {
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    showAlert('success', 'Published products updated successfully');
                } else {
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    showAlert('success', 'Featured products updated successfully');
                } else {
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        // function sort_products(el) {
        //     $('#sort_products').submit();
        // }

        function filter_products(el) {
            $('#filter_products').submit();
        }

        $.fn.editable.defaults.mode = 'inline';


        $('.updateData').editable({
            url: "{{ route('products.updatePriceOrStock') }}",
            type: 'text',
            pk: 1,
            name: 'name',
            title: 'Enter name',
            success: function(res) {
                console.log(res);
                if (res.success == true) {
                    toastr.success(res.message);
                } else if (res.success == false) {
                    toastr.error(res.message);
                }
            }
        });
        $(document).ready(function() {
            // let editableProductTable = $("#productTable").editableTable({
            //     columns: [{
            //             index: 3,
            //             name: 'stock',
            //             afterChange: function() {
            //                 alert('Stock is changed');
            //             }
            //         },
            //         {
            //             index: 4,
            //             name: 'price',
            //             afterChange: function() {
            //                 alert('price is changed');
            //             }
            //         }
            //     ]
            // });
        });


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
                        url: "{{ route('products.bulkDelete') }}",
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
                                alert(data['error']);
                            } else {
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
    </script>
@endsection
