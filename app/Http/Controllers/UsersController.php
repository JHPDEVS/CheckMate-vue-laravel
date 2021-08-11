<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    // team_id 별 전체 사용자
    public function read() {
        // 읽기 권한 검사
        
        $users = [
            'none' => User::all()->where('current_team_id', 1),
            'wdj' => User::all()->where('current_team_id', 2),
            'cpj' => User::all()->where('current_team_id', 3),
            'professor' => User::all()->where('current_team_id', 4)
        ];

        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    // 사용자 정보 수정
    public function update(Request $request, $selected_user_id) {
        // 수정 권한 검사

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'sid' => 'required|integer',
            'current_team_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'states' => 'false',
                'message' => $validator->errors()->toJson()
            ], 400);
        }

        try {
            $user = User::find($selected_user_id);
            $user->update($request->all());
        } catch(QueryException $e){
            return response()->json([
                'states' => 'false',
                'message' => $e->getMessage()
            ], 403);
        }

        $user->save();

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    // 달리기 횟수 반별 랭킹
    public function theMostestRunner() {
        $wdj_runners = $this->getRunnersByClass(2);
        $cpj_runners = $this->getRunnersByClass(3);

        $runners = [
            'wdj' => $wdj_runners,
            'cpj' => $cpj_runners,
        ];

        return response()->json([
            'status' => 'success',
            'runners' => $runners
        ], 200);
    }

    // 반별 유저 + 달리기 총 횟수 테이블
    protected function getRunnersByClass($class_value) {
        return DB::table('users')
        ->join('runs', 'users.id', '=', 'runs.user_id')
        ->where('users.current_team_id', $class_value)
        ->select('users.*', 'runs.totalRun')
        ->orderBy('totalRun', 'desc')
        ->get();
    }

    // 결석 횟수 반별 랭킹
    public function theMostestAbsentee() {
        $attends = $this->getAttends('결석');

        $wdj_absentees = $this->getUsersByClass($attends, 2);
        $cpj_absentees = $this->getUsersByClass($attends, 3);

        $absentees = [
            'wdj' => $wdj_absentees,
            'cpj' => $cpj_absentees,
        ];

        return response()->json([
            'status' => 'success',
            'absentees' => $absentees,
        ], 200);
    }

    // 지각 횟수 반별 랭킹
    public function theMostestLatecomer() {
        $attends = $this->getAttends('지각');

        $wdj_latecomers = $this->getUsersByClass($attends, 2);
        $cpj_latecomers = $this->getUsersByClass($attends, 3);

        $latecomers = [
            'wdj' => $wdj_latecomers,
            'cpj' => $cpj_latecomers,
        ];

        return response()->json([
            'status' => 'success',
            'latecomers' => $latecomers,
        ], 200);
    }

    // 지각 or 결석 명단 뽑아온 테이블
    protected function getAttends($where_value) {
        return DB::table('attends')
        ->selectRaw("user_id, count(*) as total_count")
        ->where('attends.desc_value', $where_value)
        ->groupBy('user_id');
    }

    // 반별 유저 + (지각 or 결석) 총 횟수 테이블
    protected function getUsersByClass($attends, $class_value) {
        return User::query()
        ->joinSub($attends, 'attends', function($join) {
            $join->on('id', '=', 'attends.user_id');
        })
        ->where('users.current_team_id', $class_value)
        ->orderBy('attends.total_count', 'desc')
        ->get();
    }

    // 출석 현황 최근 3개
    public function getAttendanceStatus($selected_user_id) {
        $attends = User::find($selected_user_id)
        ->attends()
        ->latest()
        ->take(3)
        ->get();

        return response()->json([
            'status' => 'success',
            'attends' => $attends,
        ], 200);
    }
}