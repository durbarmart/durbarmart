<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;

class SocialController extends Controller
{
    public function redirectToProvider()
    {
     return Socialite::driver('facebook')->redirect();
    }
    public function redirectToProviderGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleProviderCallback()
    {
	
       	$userSocial = Socialite::driver('facebook')->stateless()->user();
        $findUser = User::where('remember_token',$userSocial->id)->orWhere('email',$userSocial->email)->first();
        if($findUser)
        {
            Auth::login($findUser);
            return redirect()->route('home')->with('status','Successfully Login');
        }
        else
        {
            $user = new User();
            $user->name = $userSocial->name;
            $user->email = $userSocial->email;
			$user->remember_token = $userSocial->id;
            $user->password = bcrypt('password');
            $user->email_verified_at = date("Y-m-d h:i:a");
            $user->save();
            Auth::login($user);
            return redirect()->route('home')->with('status','Successfully Registered');
        }
    }
    public function handleProviderCallbackGoogle()
    {
        try{
            $userSocial = Socialite::driver('google')->stateless()->user();
            $findUser = User::where('remember_token',$userSocial->id)->orWhere('email',$userSocial->email)->first();   
        }catch(\Throwable $e){
            dd($e->getMessage(),'Throwable');
        }catch(\Exception $e){
            dd($e->getMessage(),'Exception');
        }
        if($findUser)
        {
            Auth::login($findUser);
            return redirect()->route('home')->with('status','Successfully Login');
        }
        else
        {
            $user = new User();
            $user->name = $userSocial->name;
            $user->email = $userSocial->email;
			$user->remember_token = $userSocial->id;
            $user->password = bcrypt('password');
            $user->email_verified_at = date("Y-m-d h:i:a");
            $user->save();
            Auth::login($user);
            return redirect()->route('home')->with('status','Successfully Registered');
        }
    }
}
