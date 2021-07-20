<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //
  
    public function redirect() {
        return Socialite::driver('kakao')->redirect();
    }

    public function callback() {
        $userSocial = Socialite::driver('kakao')->user();

        $users = User::where(['email'=>$userSocial->getEmail(),'provider'=>'kakao'])->first();

        if($users)
        {
            Auth::login($users);
            return redirect('/');
        } 
        else {
           // dd($userSocial);

            // $user = User::create([
            //     'realname' => $userSocial->getName(),
            //     'name' => $userSocial->name,
            //     'password' => '1234',
            //     'email' => $userSocial->getEmail(),
            //     'userid' => $userSocial->getid(),
            //     'provider_id' => $userSocial->getId(),
            //     'provider' => 'kakao',

                
            // ]);

            $user = new User;
            $user->name = $userSocial->name;
            $user->password = '1234';
            $user->email = $userSocial->getEmail();
            $user->provider = 'kakao';
            $user->current_team_id = '1';
            $user->save();

            $users = User::where(['email'=>$userSocial->getEmail(),'provider'=>'kakao'])->first();

            Auth::login($users);
            return redirect('/');
        }
    }
}
