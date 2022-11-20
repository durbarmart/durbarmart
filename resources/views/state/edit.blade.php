@extends('layouts.app')

@section('content')
<div>
    <h1 class="page-header text-overflow">Edit Product</h1>
</div>
<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<form class="form form-horizontal mar-top" action="{{ route('state.update', $state->id) }}" method="POST" enctype="multipart/form-data" id="choice_form">
			@csrf
			@method('put')
			<input type="hidden" name="added_by" value="admin">
			<div class="panel">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-lg-2 control-label">{{__('Name')}}</label>
						<div class="col-lg-7">
							<input type="text" class="form-control" name="name" value="{{ old('name', $state->name) }}">
							@error('name')
							<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">{{__('Select Country')}}</label>
						<div class="col-lg-7">
							<select name="country_id" class="form-control" id="">
								<option value="">Select Country</option>
								@foreach($countries as $country)
								<option value="{{ $country->id }}" {{ old('country_id', $state->country_id) == $country->id ? 'selected': '' }}>
									{{ $country->name }}
								</option>
								@endforeach
							</select>
							@error('country_id')
							<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
					</div>
				</div>
			</div>
			<div class="mar-all text-right">
				<button type="submit" name="button" class="btn btn-info">{{ __('Add New State') }}</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')


@endsection
