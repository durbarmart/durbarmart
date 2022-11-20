@extends('layouts.app')

@section('content')

    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('Footer color')}}</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Add Color
                  </button>
                  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                          <form action="{{route('generalsettings.color.add')}}" method="post">
                              <div class="modal-content">
                                  <div class="modal-header d-flex">
                                      <h5 class="modal-title" id="exampleModalLabel">Add Color</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <div class="row">
                                              @csrf
                                              <div class="form-group">
                                                  <label for="name">Title</label>
                                                  <input class="form-control" id="name" type="text" placeholder="Title" name="name">
                                              </div>
                                              <div class="form-group">
                                                  <label for="code">Code</label>
                                                  <input class="form-control" type="color" name="code" id="code">
                                              </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button class="btn btn-primary"  type="submit">Save changes</button>
                                  </div>
                              </div>                   
                          </form>
                      </div>
                  </div>
            </div>

            @php
                $colors=\App\Color::get();
                // dd($colors);
            @endphp
            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('generalsettings.color.store') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="panel-body">
                    <div class="row">
                        @foreach ($colors as $color)
                            <div class="color-radio col-sm-3">
                                <label>
                                    <input type="radio" name="frontend_color" class="color-control-input" value="{{$color->id}}" @if(\App\GeneralSetting::first()->frontend_color == $color->id) checked @endif>
                                    <span class="color-control-box" style="background:{{$color->code}}"></span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{__('save')}}</button>
                </div>
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

@endsection
