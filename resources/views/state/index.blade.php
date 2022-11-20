@extends('layouts.app')

@section('content')
        <div class="row">
            <div class="col-lg-12 pull-right">
                <a href="{{ route('state.create') }}"
                    class="btn btn-rounded btn-info pull-right">{{ __('Add New State') }}</a>
            </div>
        </div>
   

<br>
<div class="panel">
    <!--Panel heading-->
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{ __('States') }}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_products" action="" method="GET">
                    <div class="box-inline pad-rgt pull-left">
                        <div class="" style="min-width: 200px;">
                            <input type="text" class="form-control" id="search" name="search"
                                @isset($sort_search) value="{{ $sort_search }}" @endisset
                                placeholder="Type & Enter">
                        </div>
                    </div>
                </form>
                {{-- <button class="btn btn-primary" id="bulkDelBtn" onclick="deleteBulkData();">Delete</button> --}}
            </div>
        </div>


        <div class="panel-body">
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%" id="productTable">
                <thead>
                    <tr>
            
                        <th>#</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>{{ __('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($states as $key => $state)
                        <tr>
                            
                            <td>{{ ($key+1) + ($states->currentPage() - 1)*$states->perPage() }}</td>
                            <td>{{ $state->name }}</td>
                            <td>{{ $state->country->name }}</td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                        data-toggle="dropdown" type="button">
                                        {{ __('Actions') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                       
                                            <li><a
                                                    href="{{ route('state.edit', $state->id) }}">Edit</a>
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
                    {{ $states->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    
@endsection
