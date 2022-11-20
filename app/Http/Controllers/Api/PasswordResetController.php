<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
                'success' => false,
                'message' => 'We can not find a user with that e-mail address'], 404);

        $count = PasswordReset::where('email',$user->email)->first();
        if(!$count){
            $passwordReset = PasswordReset::create(
                [
                    'email' => $user->email,
                    'token' => Str::random(60)
                ]
            );
        }else{
            $passwordReset = PasswordReset::where('email',$user->email)->update(['token'=> Str::random(60)]);
            $passwordReset = PasswordReset::where('email',$user->email)->first();
            // $passwordReset = PasswordReset::update(
            //     [
            //         'email' => $user->email,
            //         'token' => Str::random(60)
            //     ]
            // );

        }

        // return $passwordReset;
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );

        return response()->json([
            'success' => true,
            'status'=>200,
            'message' => 'Please check your email. We have e-mailed your password reset link'
        ], 200);
    }
    public function change(Request $request){
        $user = User::where('id', Auth::user()->id)->first();

        if($request->password != null){
            if (Hash::check($request->old_password, $user->password)) { 
                $user->password = Hash::make($request['password']);
            }else{                  
                return response()->json([
                    'success' => false,
                    'status'=>200,
                    'message' => 'Old password doesnot match'
                ], 200);          
                // flash(__('Old password doesnot match'))->error();
                // return back();
            }
        }
        // else{            
        //     flash(__('Passwords donot match'))->error();
        //     return back();
        // }

        // $user->password = Hash::make($request['password']);
        if($user->save()){            
            return response()->json([
                'success' => true,
                'status'=>200,
                'message' => 'Password changed successfully'
            ], 200);
        }       
        return response()->json([
            'success' => false,
            'status'=>200,
            'message' => 'Something went wrong.'
        ], 200);
        // $user = User::where('id', Auth::user()->id)->first();
        // $user->password = Hash::make($request['password']);
        // if($user->save()){            
        //     return response()->json([
        //         'success' => true,
        //         'status'=>200,
        //         'message' => 'Password changed successfully'
        //     ], 200);
        // }       
        // return response()->json([
        //     'success' => false,
        //     'status'=>200,
        //     'message' => 'Something went wrong.'
        // ], 200);
    }
}
