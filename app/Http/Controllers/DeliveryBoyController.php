<?php

namespace App\Http\Controllers;

use App\DeliveryBoy;
use App\Models\Country;
use App\Upload;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliveryBoyController extends Controller
{
    private $avatarDest = 'deliveryboy/avatar/';
    private $drivingLicenseDest = 'deliveryboy/driving_license/';
    private $rcDest = 'deliveryboy/rc_book/';

    public function index(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;

        $delivery_boys = DeliveryBoy::paginate(15);
        if ($request->search != null) {
            $delivery_boys = DeliveryBoy::where('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('middle_name', 'like', '%'. $request->search. '%')
            ->orWhere('last_name', 'like', '%'. $request->search. '%')
            ->paginate(15);
            $sort_search = $request->search;
        }

        

        return view('delivery_boy.index', compact('delivery_boys', 'col_name', 'query', 'sort_search'));
    }


    public function create()
    {
        $countries = Country::all();
        return view('delivery_boy.create', compact('countries'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'first_name' => ['required'],
            'middle_name' => ['nullable'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:delivery_boys,email'],
            'phone_number' => ['required'],
            'avatar' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
            'dob' => ['required', 'date'],
            'blood_group' => ['nullable'],
            'commission' => ['nullable'],
            'password' => ['required', 'min:8'],
            'availability_status' => ['required'],
            'active_status' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'country_id' => ['required'],
            'state_id' => ['required'],
            'driving_license_no' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
            'vechile_rc_book_no' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
        ],[
            'password.required' => 'Pincode is required',
            'password.min' => 'Pincode must be 8 character',
            'country_id.required' => 'Country field is required',
            'state_id.required' => 'State field is required',
            'driving_license_no.mimes' => 'Image support jpg, png, jpeg',
            'vechile_rc_book_no.mimes' => 'Image support jpg, png, jpeg',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if($validator->passes()){
            $input = $request->except("_token");
            $input["password"] = Hash::make($request->password);
            if ($request->hasFile("avatar")) {
                $avatar = Upload::image($request, "avatar", $this->avatarDest);
                $avatarName = $input["avatar"] = $avatar["imageName"];
            }
            if ($request->hasFile("driving_license_no")) {
                $drivingLicense = Upload::image($request, "driving_license_no", $this->drivingLicenseDest);
                $drivingLicenseName = $input["driving_license_no"] = $drivingLicense["imageName"];
            }
            if ($request->hasFile("vechile_rc_book_no")) {
                $rc = Upload::image($request, "vechile_rc_book_no", $this->rcDest);
                $rcName = $input["vechile_rc_book_no"] = $rc["imageName"];
            }
            $user = [
                'user_type' => 'delivery',
                'name' => $request->first_name,
                'email' => $request->email,
                'email_verified_at' => Carbon::now(),
                'password' =>  Hash::make($request->password),
                'avatar' => $input["avatar"],
                'postal_code' => $request->zip_code,
            ];

            $user = User::create($user);
            $input['user_id'] = $user->id;
            DeliveryBoy::create($input);
            if ($request->hasFile("avatar")) {
                $avatar["image"]->move($this->avatarDest, $avatarName);
            }
            if ($request->hasFile("driving_license_no")) {
                $drivingLicense["image"]->move($this->drivingLicenseDest, $drivingLicenseName);
            }
            if ($request->hasFile("vechile_rc_book_no")) {
                $rc["image"]->move($this->rcDest, $rcName);
            }
            flash('Delivery boy created successfully')->success();
            return redirect()->route('deliveryboy.index');
        }
    }

    public function edit($id)
    {
        $delivery_boy = DeliveryBoy::findOrFail($id);
        $countries = Country::all();
        return view('delivery_boy.edit', compact('delivery_boy', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => ['required'],
            'middle_name' => ['nullable'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:delivery_boys,email,'.$id],
            'phone_number' => ['required'],
            'avatar' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
            'dob' => ['required', 'date'],
            'blood_group' => ['nullable'],
            'commission' => ['nullable'],
            'password' => ['nullable', 'min:8'],
            'availability_status' => ['required'],
            'active_status' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'country_id' => ['required'],
            'state_id' => ['required'],
            'driving_license_no' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
            'vechile_rc_book_no' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
        ],[
            'password.min' => 'Pincode must be 8 character',
            'country_id.required' => 'Country field is required',
            'state_id.required' => 'State field is required',
            'driving_license_no.mimes' => 'Image support jpg, png, jpeg',
            'vechile_rc_book_no.mimes' => 'Image support jpg, png, jpeg',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if($validator->passes()){
            $delivery_boy = DeliveryBoy::find($id);
            $input = $request->except("_token");
            $oldAvatar = $delivery_boy->avatar;
            $oldLicense = $delivery_boy->driving_license_no;
            $oldRC = $delivery_boy->vechile_rc_book_no;
            $oldPassword = $delivery_boy->password;
            if ($request->has("password")) {
                $input["password"] = Hash::make($request->password);
            } else {
                $input["password"] = $oldPassword;
            }
            if ($request->hasFile("avatar")) {
                $avatar = Upload::image($request, "avatar", $this->avatarDest);
                $avatarName = $input["avatar"] = $avatar["imageName"];
            }
            if ($request->hasFile("driving_license_no")) {
                $drivingLicense = Upload::image($request, "driving_license_no", $this->drivingLicenseDest);
                $drivingLicenseName = $input["driving_license_no"] = $drivingLicense["imageName"];
            }
            if ($request->hasFile("vechile_rc_book_no")) {
                $rc = Upload::image($request, "vechile_rc_book_no", $this->rcDest);
                $rcName = $input["vechile_rc_book_no"] = $rc["imageName"];
            }
            $delivery_boy->update($input);
            if ($request->hasFile("avatar")) {
                FileUnlink($this->avatarDest, $oldAvatar);
                $avatar["image"]->move($this->avatarDest, $avatarName);
            }
            if ($request->hasFile("driving_license_no")) {
                FileUnlink($this->drivingLicenseDest, $oldLicense);
                $drivingLicense["image"]->move($this->drivingLicenseDest, $drivingLicenseName);
            }
            if ($request->hasFile("vechile_rc_book_no")) {
                FileUnlink($this->rcDest, $oldRC);
                $rc["image"]->move($this->rcDest, $rcName);
            }
            flash('Delivery boy created successfully')->success();
            
            return redirect()->route('deliveryboy.index');
            
        }
    }
}
