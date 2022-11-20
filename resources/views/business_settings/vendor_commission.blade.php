@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="panel">
                <!--Horizontal Form-->
                <form class="form-horizontal" action="{{ route('business_settings.vendor_commission.update') }}" method="POST" enctype="multipart/form-data">
                	@csrf
                    <div class="panel-body">
                        <div class="form-group">
                            <input type="hidden" name="type" value="{{ $business_settings->type }}">
                            <label class="col-lg-3 control-label">{{__('Seller Commission')}}</label>
                            <div class="col-lg-7">
                                <input type="number" min="0" step="0.01" value="{{ $business_settings->value }}" placeholder="{{__('Seller Commission')}}" name="value" class="form-control">
                            </div>
                            <div class="col-lg-1">
                                <option class="form-control">%</option>
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
                <div class="panel-heading bord-btm">
                    <h3 class="panel-title">{{__('Note')}}</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            1. {{ $business_settings->value }}% of seller product price will be deducted from seller earnings.
                        </li>
                        <li class="list-group-item">
                            1. This commission only works when Category Based Commission is turned off from Business Settings.
                        </li>
                        <li class="list-group-item">
                            1. Commission doesn't work if seller package system add-on is activated.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">{{__('Commission (Category Wise)')}}</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ route('categories.update.rate') }}" method="POST">
                        <div class="form-group">
                            <div class="col-lg-4">
                                <label class="control-label">{{__('Status')}}</label>
                            </div>
                            <div class="col-lg-4">
                                <label class="switch">
                                    <input type="checkbox" onchange="updateSettings(this, 'category_wise_commission')" <?php if(\App\BusinessSetting::where('type', 'category_wise_commission')->first()->value == 1) echo "checked";?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-lg-4 text-right">
                                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                            </div>
                        </div>
                        @csrf
                        @foreach ($categories->get() as $key => $category)
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <input type="hidden" name="arr[{{ $key }}][id]" value="{{ $category->id }}">
                                    <input type="text" class="form-control" value="{{ $category->name }}" readonly>
                                </div>
                                <div class="col-lg-4">
                                    <input type="number" min="0" step="0.01" class="form-control" name="arr[{{ $key }}][commission_rate]" value="{{ $category->commision_rate }}">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" class="form-control" value="%">
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function updateSettings(el, type){
            if($(el).is(':checked')){
                var value = 1;
            }
            else{
                var value = 0;
            }
            $.post('{{ route('business_settings.update.activation') }}', {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
                if(data == '1'){
                    showAlert('success', 'Settings updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
