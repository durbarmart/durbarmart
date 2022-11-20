<?php

namespace App\Http\Controllers\API\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::guard('delivery-api')->check()) {
            return response()->json(['errors' => "Delivery Boy is already logged in."]);
        }
        
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|email',
            'pincode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if ($validator->passes()) {

            if (Auth::user()) {
                return response()->json(['success' => 'Delivery boy is already logged in.']);
            } else {
                $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'pin_code';
                $credentials = array('email' => $request->device_id, 'password' => $request->pincode);
                $delivery = Auth::guard('delivery')->attempt($credentials);
                if ($delivery) {
                    config(['auth.guards.api.provider' => 'delivery']);
                    $delivery = Auth::guard('delivery')->user();
                    $success = $delivery;
                    $success['token'] = $delivery->createToken('Delivery Boy', ['delivery-boy'])->accessToken;
                    return response()->json(['data' => $success, 'message' => 'Delivery Boy logged in successfully'],200);
                } else {
                    return response()->json(['errors' => 'Username or password do not match our records'], 200);
                }
            }
        }
    }

    public function logout(Request $request)
    {
        $delivery = Auth::user();
        $delivery->token()->revoke();
        Auth::guard('delivery')->logout();
        return response()->json(['data' => $delivery, 'message' => 'Delivery Boy logged out successfully.'], 200);
    }
}
