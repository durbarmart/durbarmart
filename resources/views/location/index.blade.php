@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('locations.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Locations')}}</a>
    </div>
</div>

<br>

<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Location')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table res-table table-responsive mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('State')}}</th>
                    <th>{{ __('Address') }}</th>
                    <th>{{ __('Delivery Charge(In Rs.)') }}</th>
                    <th>{{__('created_by')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locations as $location)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$location->state}}</td>
                    <td>{{$location->name}}</td>
                    <td>{{$location->delivery_charge}}</td>
                    <td>{{$location->created_by}}</td>
                    <form action="{{route('locations.destroy',$location->id)}}" method="post">
                        @csrf
                        @method('delete')
                    <td>
                        <div class="btn-group dropdown">
                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                {{__('Actions')}} <i class="dropdown-caret"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{route('locations.edit', encrypt($location->id))}}">{{__('Edit')}}</a></li>
                                <li><a><button type="submit" style="background:none; border:none; padding:0;">{{__('Delete')}}</button></a></li>
                            </ul>
                        </div>
                    </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $locations->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection