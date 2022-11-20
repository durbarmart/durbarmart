@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('subsubsubcategories.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Sub Sub Subcategory')}}</a>
    </div>
</div>

<br>
@php
    //   $b = "Diamond Nano 2 Jar Mixer And )Grinder - 500 Watt";
    //                 $x = str_replace(')','',$b);

    //                 $y = str_replace('-',' ',$x);
                    
    //                 $z = (preg_replace('/[^A-Za-z0-9\-]/', ' ', $y));
                    
    //                 $w = preg_replace('!\s+!', ' ', $z);
    //                 dd($w);
    //                 dd(str_replace(' ','-',strtolower(trim($w))));
@endphp

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Sub-Sub-Sub-categories')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_subsubcategories" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type name & Enter">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Sub Sub Subcategory')}}</th>
                    <th>{{__('SubSubcategory')}}</th>
                    <th>{{__('SubCategory')}}</th>
                    <th>{{__('Category')}}</th>
                    {{-- <th>{{__('Attributes')}}</th> --}}
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subsubsubcategories as $key => $subsubsubcategory)
                    @if ($subsubsubcategory->parentSubSub != null && $subsubsubcategory->parentSubSub->parentSub->parentCat != null)
                        <tr>
                            <td>{{ ($key+1) + ($subsubsubcategories->currentPage() - 1)*$subsubsubcategories->perPage() }}</td>
                            <td><a href="{{route('products.collectionSlug',$subsubsubcategory->slug)}}" target="_blank">{{__($subsubsubcategory->name)}}</a></td>
                            <td><a href="{{route('products.collectionSlug',$subsubsubcategory->parentSubSub->slug)}}" target="_blank">{{__($subsubsubcategory->parentSubSub->name)}}</a></td>
                            <td><a href="{{route('products.collectionSlug',$subsubsubcategory->parentSubSub->parentSub->slug)}}" target="_blank">{{$subsubsubcategory->parentSubSub->parentSub->name}}</a></td>
                            <td><a href="{{route('products.collectionSlug',$subsubsubcategory->parentSubSub->parentSub->parentCat->slug)}}" target="_blank">{{$subsubsubcategory->parentSubSub->parentSub->parentCat->name}}</a></td>
                            {{-- <td>{{__($subsubsubcategory->meta_title)}}</td>
                            <td>{{__($subsubsubcategory->meta_description)}}</td> --}}
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('subsubsubcategories.edit', encrypt($subsubsubcategory->id))}}">{{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('subsubsubcategories.destroy', $subsubsubcategory->id)}}');">{{__('Delete')}}</a></li>
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
                {{ $subsubsubcategories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
