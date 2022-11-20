@extends('layouts.app')

@section('content')
        <div class="row">
            <div class="col-lg-12 pull-right">
                <a href="{{ route('deliveryboy.create') }}"
                    class="btn btn-rounded btn-info pull-right">{{ __('Add New Delivery Boy') }}</a>
            </div>
        </div>
   

<br>
<div class="panel">
    <!--Panel heading-->
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{ __('Delivery Boys') }}</h3>
        <div class="pull-right clearfix">
            {{-- <form class="" id="sort_products" action="" method="GET">
                @if($type == 'Seller')
                    <div class="box-inline pad-rgt pull-left">
                        <div class="select" style="min-width: 200px;">
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
                    </div>
                    <div class="box-inline pad-rgt pull-left">
                        <div class="" style="min-width: 200px;">
                            <input type="text" class="form-control" id="search" name="search"
                                @isset($sort_search) value="{{ $sort_search }}" @endisset
                                placeholder="Type & Enter">
                        </div>
                    </div>
                </form> --}}
                {{-- <button class="btn btn-primary" id="bulkDelBtn" onclick="deleteBulkData();">Delete</button> --}}
            </div>
        </div>


        <div class="panel-body">
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%" id="productTable">
                <thead>
                    <tr>
                        {{-- ['first_name', 'middle_name', 'last_name','email', 'phone_number','avatar',
     'dob', 'blood_group','commission','password', 'active_status', 'availability_status', 'address',
      'city', 'country_id', 'state_id', 'zip_code', 'vechile_name', 'owner_name', 'vechile_color',
       'vechile_registration_no', 'vechile_details','driving_license_no', 'vechile_rc_book_no','account_name',
        'account_number','gpay_number','bank_address','ifsc_code', 'branch_name']; --}}
                        {{-- <th><input type="checkbox" id="checkAll"></th> --}}
                        <th>#</th>
                        <th>Avatar</th>
                        <th>{{ __('First Name') }}</th>
                        
                        <th>{{ __('Last Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone Number') }}</th>
                        <th>{{ __('Dob') }}</th>
                        <th>{{ __('Blood Group') }}</th>
                        {{-- <th>{{ __('Country') }}</th>
                        <th>{{ __('State') }}</th> --}}
                        <th>{{ __('Vechile Name') }}</th>
                        <th>{{ __('Owner Name') }}</th>
                        <th>{{ __('Vechile Color') }}</th>
                        <th>{{ __('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($delivery_boys as $key => $delivery_boy)
                        <tr>
                            {{-- <td>
                                <input type="checkbox" value="{{ $product->id }}" data-id="{{ $product->id }}"
                                name="productID[]" class="rowCheck">
                            </td> --}}
                            <td>{{ ($key+1) + ($delivery_boys->currentPage() - 1)*$delivery_boys->perPage() }}</td>
                            <td>
                                <a href="" target="_blank" class="media-block">
                                    <div class="media-left">
                                        
                                            <img loading="lazy" src="{{ $delivery_boy->getAvatar($delivery_boy->avatar) }}"  class="img-md" src="" alt="Image">
                                                
                                        
                                    </div>
                                    {{-- <div class="media-body">{{ __($product->name) }}</div> --}}
                                </a>
                            </td>
                            <td>{{ $delivery_boy->first_name }}</td>
                            <td>{{ $delivery_boy->last_name }}</td>
                            <td>{{ $delivery_boy->email }}</td>
                            <td>{{ $delivery_boy->phone_number }}</td>
                            <td>{{ $delivery_boy->dob }}</td>
                            <td>{{ $delivery_boy->blood_group }}</td>
                            {{-- <td>{{ $delivery_boy->country->name }}</td> --}}
                            {{-- <td>{{ $delivery_boy->state->name }}</td> --}}
                            <td>{{ $delivery_boy->vechile_name }}</td>
                            <td>{{ $delivery_boy->owner_name }}</td>
                            <td>{{ $delivery_boy->vechile_color }}</td>
                            
                            
                           
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                        data-toggle="dropdown" type="button">
                                        {{ __('Actions') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                       
                                            <li><a
                                                    href="{{ route('deliveryboy.edit', $delivery_boy->id) }}">{{ __('Edit') }}</a>
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
                    {{ $delivery_boys->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    
@endsection
