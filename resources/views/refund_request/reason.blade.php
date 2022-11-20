@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title text-center">{{__('Reason For Refund Request')}}</h3>
            </div>
            <div class="panel-body">
                
                <div class="form-group row">
                    <label class="col-lg-2 control-label">{{__('Reason')}}</label>
                    <div class="col-lg-8">
                        <p class="bord-all pad-all">{{ $refund->reason }}</p>
                    </div>
                </div>
                @if (!empty($refund->admin_comment))
                <div class="form-group row">
                    <label class="col-lg-2 control-label">{{__('Admin Comment')}}</label>
                    <div class="col-lg-8">
                        <p class="bord-all pad-all">{{$refund->admin_comment}}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Add Comment
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Comment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="" action="{{route('admin_comment', $refund->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
                @csrf
                <div class="form-box bg-white mt-4">
                    <div class="form-box-content p-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{__('Refund Reason')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <textarea name="admin_comment" rows="6" class="form-control mb-3">{{$refund->admin_comment}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-box mt-4 text-right" style="margin-top:10px">
                    <button type="submit" class="btn btn-primary">{{ __('Add Comment') }}</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
