<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    // team_id 별 전체 사용자
    public function read()
    {
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
    public function update(Request $request, $selected_user_id)
    {
        // 수정 권한 검사

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'sid' => 'required|integer',
            'current_team_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        try {
            $user = User::find($selected_user_id);
            $user->update($request->all());
        } catch (QueryException $e) {
            return response()->json([
                'states' => 'false',
                'message' => $e->getMessage()
            ], 200);
        }

        $user->save();

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    // 달리기 횟수 반별 랭킹
    public function theMostestRunner()
    {
        $wdj_runners = $this->getRunnersByClass(2);
        $cpj_runners = $this->getRunnersByClass(3);

        $runners = [
            'wdj' => $wdj_runners,
            'cpj' => $cpj_runners,
        ];

        return response()->json([
            'status' => 'success',
            'runners' => $runners,
        ], 200);
    }

    // 반별 유저 + 달리기 총 횟수 테이블
    protected function getRunnersByClass($class_value)
    {
        return DB::table('users')
            ->join('runs', 'users.id', '=', 'runs.user_id')
            ->where('users.current_team_id', $class_value)
            ->select('users.*', 'runs.totalRun')
            ->orderBy('totalRun', 'desc')
            ->paginate(3);
    }

    // 결석 횟수 반별 랭킹
    public function theMostestAbsentee()
    {
        $attendState = '결석';
        $attends = $this->getAttends($attendState);

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
    public function theMostestLatecomer()
    {
        $attendState = '지각';
        $attends = $this->getAttends($attendState);

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
    protected function getAttends($where_value)
    {
        return DB::table('attends')
            ->selectRaw("user_id, count(*) as total_count")
            ->where('attends.desc_value', $where_value)
            ->groupBy('user_id');
    }

    // 반별 유저 + (지각 or 결석) 총 횟수 테이블
    protected function getUsersByClass($attends, $class_value)
    {
        return User::query()
            ->joinSub($attends, 'attends', function ($join) {
                $join->on('id', '=', 'attends.user_id');
            })
            ->where('users.current_team_id', $class_value)
            ->orderBy('attends.total_count', 'desc')
            ->paginate(4);
    }

    // 출석 현황 최근 3개
    public function getAttendanceStatus($user_id)
    {
        $attends = User::find($user_id)
            ->attends()
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'attends' => $attends,
        ], 200);
    }

    // 사용자 달리기, 출석 현황
    public function getUserStatus(Request $request, $user_id)
    {
        $attend = $request->query('attend'); // 출석, 지각, 결석, 전체

        if ($attend !== "전체") {
            $user_attend = User::find($user_id)
                ->attends()
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %T') as date"),
                    DB::raw('id, user_id, run, device_id, desc_value, attend, updated_at'),
                )
                ->orderBy('created_at', 'desc')
                ->where('desc_value', $attend)
                ->paginate(10);
        } else if ($attend === "전체") {
            $user_attend = User::find($user_id)
                ->attends()
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %T') as date"),
                    DB::raw('id, user_id, run, device_id, desc_value, attend, updated_at'),
                )
                ->orderBy('created_at', 'desc')
                ->paginate(3);
        }

        $user_run = User::find($user_id)
            ->run()
            ->orderBy('totalRun', 'desc')
            ->paginate(3);

        return response()->json([
            'status' => 'success',
            'user_attend' => $user_attend,
            'user_run' => $user_run,
            'attend' => $attend
        ], 200);
    }

    //  반별 일주일간 지각 or 결석 현황
    public function getUsersAttendsByDate(Request $request)
    {
        $teamId = $request->query('teamId');
        $attend = $request->query('attend');

        // 일주일 전 2021-08-17 -> 2021-08-09
        $date = Carbon::now()->previous()->toDateString();

        $data = DB::table('users')
            ->join('attends', 'users.id', '=', 'attends.user_id')
            ->where('users.current_team_id', $teamId)
            ->where('attends.desc_value', $attend)
            ->where('attends.created_at', '>=', $date)
            ->select(
                DB::raw("DATE_FORMAT(attends.created_at, '%m/%d') as date"),
                DB::raw('COUNT(*) as count'),
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    // 한달간 or 오늘 반별 출결 현황 (도넛)
    public function classAttendStatus(Request $request, $teamId)
    {
        $range = $request->query('range'); // month, today

        if ($range === 'month') {
            $range = Carbon::now()->subMonth()->toDateString(); // 2021-07-23
        } else if ($range === 'today') {
            $range = Carbon::now()->toDateString(); // 2021-08-23
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'range must month or today!',
            ], 200);
        };

        $data = DB::table('users')
            ->join('attends', 'users.id', '=', 'attends.user_id')
            ->selectRaw('attends.desc_value, count(*) as count')
            ->where('users.current_team_id', $teamId)
            ->where('attends.created_at', '>=', $range)
            ->groupBy('desc_value')
            ->get();

        return response()->json([
            'state' => 'success',
            'data' => $data,
        ]);
    }

    // 한달간 개인별 출결 현황 (도넛)
    public function userAttendStatusByMonth($userId)
    {
        $dateSubMonth = Carbon::now()->subMonth()->toDateString();
        // 2021-07-23

        $data = DB::table('users')
            ->join('attends', 'users.id', '=', 'attends.user_id')
            ->selectRaw('attends.desc_value, count(*) as count')
            ->where('users.id', $userId)
            ->where('attends.created_at', '>=', $dateSubMonth)
            ->groupBy('desc_value')
            ->get();

        return response()->json([
            'state' => 'success',
            'data' => $data,
        ]);
    }

    // 일주일간 개인별 날짜에대한 출결현황
    public function userAttendStatusByWeek($userId)
    {
        $today = Carbon::now()->format('Y-m-d');
        $subDate = Carbon::now()->subWeek()->format('Y-m-d');

        $data = DB::select(
            DB::raw("
                WITH RECURSIVE cte  AS (
                    SELECT '{$subDate}' AS date FROM DUAL
                    UNION ALL
                    SELECT date_add(date, INTERVAL 1 DAY) FROM cte
                    WHERE date < '{$today}'
                )
                SELECT u.id, u.name, a.desc_value, c.date, a.run
                FROM (SELECT id, name FROM users WHERE id = {$userId}) u
                JOIN attends a ON u.id = a.user_id
                RIGHT JOIN cte c ON c.date = DATE_FORMAT(a.created_at, '%Y-%m-%d');
            ")
        );

        return  response()->json([
            'state' => 'success',
            'data' => $data,
        ]);
    }

    // 반별 한정 전체인원
    public function attendStatusByDate(Request $request, $team_id)
    {
        $date = $request->query('date');

        $data = DB::table('users')
            ->join('attends', 'attends.user_id', '=', 'users.id')
            ->where('users.current_team_id', $team_id)
            ->where(DB::raw("(DATE_FORMAT(attends.created_at, '%Y-%m-%d'))"), $date)
            ->selectRaw("users.name, attends.desc_value, DATE_FORMAT(attends.created_at, '%Y-%m-%d %H:%i') as date")
            ->orderBy('users.id')
            ->paginate(10);

        return response()->json([
            'state' => 'success',
            'data' => $data,
        ]);
    }

    // 내가 쓴 게시글, 댓글 수 반환
    public function countMyPostsAndComments($user_id)
    {
        $myPosts = User::find($user_id)->posts()->get();
        $myComments = User::find($user_id)->comments()->get();

        $data = [
            'countMyPosts' => count($myPosts),
            'countMyComments' => count($myComments)
        ];

        return response()->json([
            'state' => 'success',
            'data' => $data,
        ]);
    }
}