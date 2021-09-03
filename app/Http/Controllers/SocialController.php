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
        return Socialite::driver('kakao')->stateless()->redirect();
    }

    public function callback() {
        $userSocial = Socialite::driver('kakao')->stateless()->user();
        $userProfile = $userSocial->user['kakao_account']['profile']['profile_image_url'];
        $users = User::where(['email'=>$userSocial->getEmail(),'provider'=>'kakao'])->first();

        if($users)
        {
            $users->profile_photo_path = $userProfile;
            $users->save();
            Auth::login($users);
            $token = $users->createToken($users->name)->plainTextToken;
            return redirect('/')->with('api_token', $token); // blade: {{ session()->get('api_token')}} php: session()->get('api_token')
        }
        else {


            $user = new User;
            $user->name = $userSocial->name;
            $user->password = Hash::make(Str::random(8));
            $user->email = $userSocial->getEmail();
            $user->provider = 'kakao';
            $user->current_team_id = '1';
            $user->profile_photo_path = $userProfile;
            $user->save();

            $users = User::where(['email'=>$userSocial->getEmail(),'provider'=>'kakao'])->first();

            Auth::login($users);
            $token = $users->createToken($users->name)->plainTextToken;
            return redirect('/setInfo')->with('api_token', $token);
        }
    }

        // 로그인 후 추가정보 입력
        public function inputData(Request $req) {

            
            $validator = Validator($req->all(), [
                '학번' => 'required|integer|digits:7',
                '이름' => 'required',
                '전화번호' => 'required|digits:11',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 'false',
                    'data' => $validator->errors()
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }


            $user = User::find(Auth::user()->id);
            $user->phone_number = $req->전화번호;
            if($req->반 == 'WDJ') {
                $user->current_team_id = 2;
            } else if($req->반 == 'CPJ'){
                $user->current_team_id = 3;
            }
            $user->class = $req->반;
            $user->sid = $req->학번;
            $user->position = $req->위치;
            $user->name = $req->이름;
            $ifUser = User::where('sid', $req->학번)->first();
            if($ifUser) {
                return response()->json([
                    'status' => '학번',
                    'data' => '이미 가입된 학번입니다'
                ], 200, [], JSON_UNESCAPED_UNICODE);
            } else {
                $user->save();
            }

            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);

        }


}