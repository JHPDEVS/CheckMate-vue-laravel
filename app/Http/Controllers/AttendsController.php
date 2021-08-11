<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use App\Models\Run;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendsController extends Controller
{
    public function attend(Request $req) {
        $validator = Validator::make($req->all(), [
            'user_sid' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $date = date("Y-m-d");
        $time = date("H:i:s");
        $point = strtotime('09:00:00');
        $timestamp = strtotime($time);
        $result = $timestamp - $point;

        $attend = new Attend();
        // $userId = Auth::user()->id; // api.php는 비저장 상태라 토큰을 받아오지 않는 한 인증이 불가
        $userId = User::where('sid', $req->user_sid)->first()->id;

        // 오늘 출결 했는지 판단 후 했다면 디비에 넣지 않고 return false
        if (Attend::where('attend', $date)->where('user_id', $userId)->get()->count() != 0) {
            return $res = response()->json([
                'status' => 'false',
                'message' => '오늘은 이미 출결 했습니다.',
            ]);
        }

        if ($result > 0) {
            $cul = number_format($result/60/5); // 바퀴 수
            $cul++;
            $attend->run = $cul;
            $attend->desc_value = '지각';

            // 첫 번째 인자 학번, 두 번째 인자 오늘 run 수 세 번째 인자 user_id
            $this->insertRun($attend->run, $userId);
        }

        $attend->user_id = $userId;
        $attend->attend = $date;
        $attend->save();


        $res = response()->json([
            'status' => 'success',
            'data' => $attend
        ], 200);

        return $res;
    }

    // 첫 번째 인자 오늘 run 두 세 번째 인자 user_id
    public function insertRun($todayRun, $userId) {
        $run = null;
        $countRun = $todayRun;
        $totalRun = User::find($userId)->attends()->sum('run');


        if (Run::where('user_id', $userId)->get()->count() == 0) {
            $run = new Run();
            $run->user_id = $userId;
        } else {
            $run = Run::where('user_id', $userId)->first();
        }

        $run->countRun = $run->countRun + $countRun;
        // 오늘 출석 데이터가 디비에 들어가기 전에 실행 되기 때문에 전날까지만 가져오므로 오늘꺼 더해준다.
        $run->totalRun = $totalRun + $countRun;

        $run->save();
    }

    // 출석하지 않은 유저들 반별로
    public function notAttendUsers(Request $req) {
        $validator = Validator::make($req->all(), [
            'class' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $date = date("Y-m-d");
        $users = User::where('class', $req->class)->get();
        $attend_users = Attend::where('attend', $date)->get();
        $users_array = $users->toArray();

        // 하루에 한명도 출석 안할 시
        if ($attend_users->count() == 0) {
            $res = response()-> json([
                'status' => 'success',
                'users' => $users_array
            ]);

            return $res;
        }

        // 한명이라도 출석 했다면
        for ($i = 0; $i < $users->count(); $i++) {
            if($users[$i]->id == $attend_users[$i]->user_id) {
                array_splice($users_array, $i);
            }
        }

        $res = response()-> json([
            'status' => 'success',
            'users' => $users_array
        ]);

        return $res;
    }

    // 결석 처리
    public function absent(Request $req) {
        $validator = Validator::make($req->all(), [
            'user_sid' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $date = date("Y-m-d");
        $userId = User::where('sid', $req->user_sid)->first()->id;

        // 오늘 출결 했는지 판단 후 했다면 디비에 넣지 않고 return false
        if (Attend::where('attend', $date)->where('user_id', $userId)->get()->count() != 0) {
            return response()->json([
                'status' => 'false',
                'message' => '오늘은 이미 출결 했습니다.',
            ]);
        }

        $absent_user = new Attend();
        $absent_user->user_id = $userId;
        $absent_user->desc_value = '결석';
        $absent_user->attend = $date;

        $absent_user->save();

        $res = response()-> json([
            'status' => 'success',
            'data' => $absent_user
        ]);

        return $res;
    }
}