<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations=Location::orderBy('created_at','desc');
        $locations = $locations->paginate(15);

        return view('location.index',compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $location=new Location;
        $location->name=$request->address;
        $location->state=$request->state;
        $location->delivery_charge=$request->delivery_charge;
        $location->created_by=Auth::user()->name;
        // $location->save();

        if($location->save()){
            // dd('hi');
            flash(__('Location has been inserted successfully'))->success();
            return redirect()->route('locations.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
        // return redirect()->route('locations.index')->with(['message' => 'Location added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $location = Location::findOrFail(decrypt($id));
        return view('location.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $location=Location::findOrFail($id);
        $location->name=$request->address;
        $location->state=$request->state;
        $location->delivery_charge=$request->delivery_charge;
        $location->created_by=Auth::user()->name;
        // $location->save();

        if($location->save()){
            // dd('hi');
            flash(__('Location has been updated successfully'))->success();
            return redirect()->route('locations.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd('hi');
        $location = Location::findOrFail($id);
        // $location->delete();
        if($location->delete()){
            flash(__('Location has been deleted successfully'))->success();
            return redirect()->route('locations.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }
}
