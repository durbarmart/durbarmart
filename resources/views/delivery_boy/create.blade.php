@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/datepicker/datepicker.min.css') }}">
@endsection
@section('content')
    <div>
        <h1 class="page-header text-overflow">Add New Delivery Boy</h1>
    </div>
    <div class="row">

        <div class="col-lg-8 col-lg-offset-2">
            <form class="form form-horizontal mar-top" action="{{ route('deliveryboy.store') }}" method="POST"
                enctype="multipart/form-data" id="choice_form">
                @csrf
                <input type="hidden" name="added_by" value="admin">
                <div class="panel">
                    <div class="panel-heading bord-btm">
                        <h3 class="panel-title">{{ __('Delivery Boy Information') }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('First Name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="{{ old('first_name') }}"
                                    name="first_name" required>
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Middle Name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="middle_name"
                                    value="{{ old('middle_name') }}">
                                @error('middle_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Last Name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="{{ old('last_name') }}" name="last_name"
                                    required>
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Phone Number') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="phone_number"
                                    value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('DOB') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control dob" value="{{ old('dob') }}" name="dob"
                                    autocomplete="off">
                                @error('dob')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Blood Group') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="blood_group"
                                    value="{{ old('blood_group') }}">
                                @error('blood_group')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="form-group hidden">
                            <label class="col-lg-2 control-label">{{ __('Commission') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="commission"
                                    value="{{ old('commission') }}">
                                @error('commission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Avatar') }}</label>
                            <div class="col-lg-7">
                                <input type="file" class="form-control" name="avatar" required>
                                @error('avatar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-heading bord-btm">
                        <h3 class="panel-title">{{ __('Login Info') }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Email Address') }}</label>
                            <div class="col-lg-7">
                                <input type="email" class="form-control" value="{{ old('email') }}" name="email"
                                    required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Pincode') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="password" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Active Status') }}</label>
                            <div class="col-lg-7">
                                <select class="form-control demo-select2-placeholder" name="active_status"
                                    id="active_status">
                                    <option value="1" {{ old('active_status') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('active_status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('active_status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Availability Status') }}</label>
                            <div class="col-lg-7">
                                <select class="form-control demo-select2-placeholder" name="availability_status"
                                    id="availability_status">
                                    <option value="1" {{ old('availability_status') == 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('availability_status') == 0 ? 'selected' : '' }}>InActive
                                    </option>
                                </select>
                                @error('availability_status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-heading bord-btm">
                        <h3 class="panel-title">{{ __('Address Info') }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Address') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                    required>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('City') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Countries') }}</label>
                            <div class="col-lg-7">
                                {{-- onchange="getStates($(this).val());" --}}
                                <select class="form-control" name="country_id" id="country" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ $country->name == 'Nepal' ? 'selected' : '' }}>
                                            {{ $country->name }}</option>
                                    @endforeach

                                </select>
                                @error('country_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('States') }}</label>
                            <div class="col-lg-7">
                                <select class="form-control" name="state_id" id="state" required>
                                    <option value="">Select State</option>
                                    <option value="1">{{__('State 1')}}</option>
                                    <option value="2">{{__('State 2')}}</option>
                                    <option value="3">{{__('State 3')}}</option>
                                    <option value="4">{{__('State 4')}}</option>
                                    <option value="5">{{__('State 5')}}</option>
                                    <option value="6">{{__('State 6')}}</option>
                                    <option value="7">{{__('State 7')}}</option>

                                </select>
                                @error('state_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Zip Code') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="zip_code" value="{{ old('zip_code') }}"
                                    required>
                                @error('zip_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-heading bord-btm">
                        <h3 class="panel-title">{{ __('Vechile Info') }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Vechile Name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="vechile_name"
                                    value="{{ old('vechile_name') }}">
                                @error('vechile_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Owner Name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="owner_name"
                                    value="{{ old('owner_name') }}">
                                @error('owner_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Vechile Color') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="vechile_color"
                                    value="{{ old('vechile_color') }}">
                                @error('vechile_color')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Vechile Registration No') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="vechile_registration_no"
                                    value="{{ old('vechile_registration_no') }}">
                                @error('vechile_registration_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Vechile Details') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="vechile_details"
                                    value="{{ old('vechile_details') }}">
                                @error('vechile_details')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Driving License') }}</label>
                            <div class="col-lg-7">
                                <input type="file" class="form-control" name="driving_license_no" required>
                                @error('driving_license_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Vechile Rc Book') }}</label>
                            <div class="col-lg-7">
                                <input type="file" class="form-control" name="vechile_rc_book_no" required>
                                @error('vechile_rc_book_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-heading bord-btm">
                        <h3 class="panel-title">{{ __('Account Details') }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Account Name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" name="account_name" class="form-control"
                                    value="{{ old('account_name') }}">
                                @error('account_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Account Number') }}</label>
                            <div class="col-lg-7">
                                <input type="text" name="account_number" class="form-control"
                                    value="{{ old('account_number') }}">
                                @error('account_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Gpay Number') }}</label>
                            <div class="col-lg-7">
                                <input type="text" name="gpay_number" class="form-control"
                                    value="{{ old('gpay_number') }}">
                                @error('gpay_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Bank Address') }}</label>
                            <div class="col-lg-7">
                                <input type="text" name="bank_address" class="form-control"
                                    value="{{ old('bank_address') }}">
                                @error('bank_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('IFSC code') }}</label>
                            <div class="col-lg-7">
                                <input type="text" name="ifsc_code" class="form-control"
                                    value="{{ old('ifsc_code') }}">
                                @error('ifsc_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ __('Branch Name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" name="branch_name" class="form-control"
                                    value="{{ old('branch_name') }}">
                                @error('branch_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mar-all text-right">
                    <button type="submit" name="button" class="btn btn-info">{{ __('Add New Delivery Boy') }}</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('script')
    <script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dob').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                todayBtn: 'linked',
                clearBtn: true,
                autoclose: true,
            });

            var country_id = "{{ old('country_id') }}";
            // log
            // if (country_id != null) {
            //     var url = "{{ route('getStates') }}";
            //     $.ajax({
            //         url: url,
            //         type: 'POST',
            //         data: {
            //             'country_id': country_id
            //         },
            //         success: function(response) {

            //             if (Object.keys(response).length > 0) {
            //                 $.each(response, function(key, value) {
            //                     $option = $('<option></option>').val(key).html(value);
            //                     if (key == '{{ old('state_id') }}')
            //                         $option = $option.attr("selected", "selected");
            //                     $("#state").append($option);
            //                 });
            //             } else {
            //                 $("#state").html('<option value="">Select State</option>');
            //             }
            //         }
            //     });
            // }
        });

        // function getStates(id) {
        //     var url = "{{ route('getStates') }}";
        //     $.ajax({
        //         url: url,
        //         type: 'POST',
        //         data: {
        //             'country_id': id
        //         },
        //         success: function(response) {

        //             if (Object.keys(response).length > 0) {
        //                 $.each(response, function(key, value) {
        //                     $("#state").append('<option value="' + key + '">' + value + '</option>');
        //                 });
        //             } else {
        //                 $("#state").html('<option value="">Select State</option>');
        //             }
        //         }
        //     });
        // }
    </script>
@endsection
