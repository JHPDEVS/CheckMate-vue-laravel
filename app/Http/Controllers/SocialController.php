<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
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


            $user = new User;
            $user->name = $userSocial->name;
            $user->password = Hash::make(Str::random(8));
            $user->email = $userSocial->getEmail();
            $user->provider = 'kakao';
            $user->current_team_id = '1';
            $user->save();

            $users = User::where(['email'=>$userSocial->getEmail(),'provider'=>'kakao'])->first();

            Auth::login($users);
            return redirect('/');
        }
    }

        // 로그인 후 추가정보 입력
        public function inputData(Request $req) {
            $validator = Validator($req->all(), [
                'phone_number' => 'required|string',
                'sid' => 'required|integer',
                'position' => 'required|string'
            ]);
    
            $res = null;
    
            if ($validator->fails()) {
                $res = response()->json([
                    'status' => 'error',
                    'messages' => $validator->getMessageBag()
                ], 200);
    
                return redirect('/')->with($res);
            }
    
            $user = User::find(Auth::user()->id);
            $user->phone_number = $req->phone_number;
            $user->sid = $req->sid;
            $user->position = $req->position;
    
            $user->save();
    
            $res = response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);
    
            return redirect()->route('', ['res' => $res]);
        }
}
