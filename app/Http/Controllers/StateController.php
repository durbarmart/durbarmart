<?php

namespace App\Http\Controllers;

use App\Country;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;

        $states = State::paginate(15);
        if ($request->search != null) {
            $states = State::where('name', 'like', '%' . $request->search . '%')
            ->paginate(15);
            $sort_search = $request->search;
        }

        

        return view('state.index', compact('states', 'col_name', 'query', 'sort_search'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('state.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'country_id' => ['required'],
            'name' => ['required'],
        ],[
            'country_id.required' => 'The country field is required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if($validator->passes()){
            $input = $request->except('_token');
            State::create($input);
            flash(__('State created successfully'))->success();
            return redirect()->route('state.index');
        }
    }

    public function edit($id)
    {
        $state = State::findOrFail($id);
        $countries = Country::all();
        return view('state.edit', compact('state','countries'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'country_id' => ['required'],
            'name' => ['required'],
        ],[
            'country_id.required' => 'The country field is required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if($validator->passes()){
            $state = State::find($id);
            $input = $request->except('_token');
            $state->update($input);
            flash(__('State updated successfully'))->success();
            return redirect()->route('state.index');
        }
    }
}
