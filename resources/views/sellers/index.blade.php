@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box-inline pad-rgt pull-left">
                <div class="">
                    <button class="btn btn-primary" id="bulkDelBtn" onclick="deleteBulkData();">Delete</button>
                </div>
            </div>
            <a href="{{ route('sellers.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Seller')}}</a>
        </div>
    </div>

    <br>

    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="panel">
        <div class="panel-heading bord-btm clearfix pad-all h-100">
            <h3 class="panel-title pull-left pad-no">{{__('Sellers')}}</h3>
            <div class="pull-right clearfix">      
                <form class="" id="sort_sellers" action="" method="GET">
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
                        <div class="select" style="min-width: 300px;">
                            <select class="form-control demo-select2" name="approved_status" id="approved_status" onchange="sort_sellers()">
                                <option value="">{{__('Filter by Approval')}}</option>
                                <option value="1"  @isset($approved) @if($approved == 'paid') selected @endif @endisset>{{__('Approved')}}</option>
                                <option value="0"  @isset($approved) @if($approved == 'unpaid') selected @endif @endisset>{{__('Non-Approved')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-inline pad-rgt pull-left">
                        <div class="" style="min-width: 200px;">
                            <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type name or email & Enter">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel-body" style="overflow-x:scroll;">
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%" >
                <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Shop Name')}}</th>
                    <th>{{__('Phone')}}</th>
                    <th>{{__('Email Address')}}</th>
                    <th>{{__('Verification Info')}}</th>
                    <th>{{__('Approval')}}</th>
                    <th>{{ __('Num. of Products') }}</th>
                    <th >{{ __('Due to seller') }}</th>
                    <th width="10%" >{{__('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sellers as $key => $seller)
                    @if($seller->user != null)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $seller->id }}" data-id="{{ $seller->id }}"
                                name="sellerId[]" class="rowCheck">
                            </td>
                            <td>{{ ($key+1) + ($sellers->currentPage() - 1)*$sellers->perPage() }}</td>
                            <td>{{$seller->user->name}}</td>
                            <td>
                                @if ($seller->user != null && $seller->user->shop != null)
                                <a href="{{route('shop.visit',$seller->user->shop->slug)}}">
                                    {{$seller->user->shop->name}}
                                </a>
                                @endif

                            </td>
                            <td>{{$seller->user->phone}}</td>
                            <td>{{$seller->user->email}}</td>
                            <td>
                                @if ($seller->verification_info != null)
                                    <a href="{{ route('sellers.show_verification_request', $seller->id) }}">
                                        <div class="label label-table label-info">
                                            {{__('Show')}}
                                        </div>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <label class="switch">
                                    <input onchange="update_approved(this)" value="{{ $seller->id }}" type="checkbox" <?php if($seller->verification_status == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>{{ \App\Product::where('user_id', $seller->user->id)->count() }}</td>
                            <td>
                                @if ($seller->admin_to_pay >= 0)
                                    {{ single_price($seller->admin_to_pay) }}
                                @else
                                    {{ single_price(abs($seller->admin_to_pay)) }} (Due to Admin)
                                @endif
                            </td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a onclick="show_seller_profile('{{$seller->id}}');">{{__('Profile')}}</a></li>
                                        <li><a onclick="show_seller_payment_modal('{{$seller->id}}');">{{__('Pay Now')}}</a></li>
                                        <li><a href="{{route('sellers.payment_history', encrypt($seller->id))}}">{{__('Payment History')}}</a></li>
                                        <li><a href="{{route('sellers.edit', encrypt($seller->id))}}">{{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('sellers.destroy', $seller->id)}}');">{{__('Delete')}}</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $sellers->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="profile_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">

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
                        url: "{{ route('seller.bulkDelete') }}",
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
            var appendUrl = '&items_per_page='+val;
            window.location.replace(current+'/admin/sellers?search='+($.urlParam('search')??'')+appendUrl);
        });
        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_seller_profile(id){
            $.post('{{ route('sellers.profile_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#profile_modal #modal-content').html(data);
                $('#profile_modal').modal('show', {backdrop: 'static'});
            });
        }

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('sellers.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Approved sellers updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }
    </script>
@endsection
