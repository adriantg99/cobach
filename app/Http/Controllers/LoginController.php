<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller {
    public function redirectToProvider() {
        return Socialite::driver('google')->redirect();
        //return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleProviderCallback(Request $request)
    {
        try {
            $user_google = Socialite::driver('google')->user();
 
            if ( !preg_match( "/^[^@ \t\r\n]+@bachilleresdesonora\.edu\.mx$/", $user_google->email ) ) {
                throw new Exception("Favor de utilizar correo institucional @bachilleresdesonora.edu.mx");
            }

            $find_user = User::where('email',$user_google->email)->first();
            if($find_user) {
                $find_user->update([                
                    'google_id'         =>  $user_google->id,
                    'google_picture'    =>  $user_google->avatar,
                    'email_verified_at' =>  date("Y-m-d H:i:s"),
                ]);
                Auth::login($find_user);
                return redirect ('/');
            } else {
                $new_user = User::create([
                    'name'              =>  $user_google->name,
                    'email'             =>  $user_google->email,
                    'email_verified_at' =>  date("Y-m-d H:i:s"),
                    'password'          =>  Hash::make('LTODO2023'),
                    'google_id'         =>  $user_google->id,
                    'google_picture'    =>  $user_google->avatar,
                ]);
                Auth::login($new_user);
                return redirect ('/');
            }
        }
        catch(\Exception $e) {
            return redirect('/login')->with('error', $e->getMessage());
        }
    }
}
