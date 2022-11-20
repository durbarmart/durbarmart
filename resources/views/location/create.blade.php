@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('locations.index')}}" class="btn btn-rounded btn-info pull-right">{{__('View Locations')}}</a>
    </div>
</div>

<br>

<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Add Location')}}</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" action="{{ route('locations.store') }}" method="POST">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="address">{{__('Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Address')}}" name="address" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="delivery_charge">{{__('Delivery Charge')}}</label>
                    <div class="col-sm-9">
                        <input type="number" min="0" value="0" step="0.01" placeholder="{{__('Delivery Charge')}}" name="delivery_charge" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label" for="state">{{__('State')}}</label>
                    <div class="col-lg-9">
                        <select name="state" class="form-control demo-select2" required>
                            <option value="">Select One</option>
                            <option value="State 1">{{__('State 1')}}</option>
                            <option value="State 2">{{__('State 2')}}</option>
                            <option value="State 3">{{__('State 3')}}</option>
                            <option value="State 4">{{__('State 4')}}</option>
                            <option value="State 5">{{__('State 5')}}</option>
                            <option value="State 6">{{__('State 6')}}</option>
                            <option value="State 7">{{__('State 7')}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
            </div>
        </form>
    </div>
</div>
@endsection