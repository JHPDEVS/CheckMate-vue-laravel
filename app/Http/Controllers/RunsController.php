<?php

namespace App\Http\Controllers;

use App\Models\Attend_posts;
use App\Models\Run;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RunsController extends Controller
{
    // user_id랑 달린 수(run)
    public function minusRun(Request $req, $selected_post_id) {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer', // 달리기 게시판 글쓴이 아이디
            'req_id' => 'required|integer', // 승인 버튼 누른 사람 아이디
            'run' => 'required|integer', // 달리기 수
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::find($req->req_id);

        if (!($user->current_team_id == 4 || $user->current_team_id == 5)) {
            $res = response()->json([
                'status' => 'false',
                'message' => '권한이 없습니다.',
            ], 200);

            return $res;
        }

        $run = Run::where('user_id', $req->user_id)->first();
        $post = Attend_posts::find($selected_post_id);

        if ($post->flag == true) {
            $post->flag = false;

            $run->countRun = $run->countRun + $req->run;
            $run->minusRun = $run->minusRun - $req->run;

            $run->save();
            $post-> save();

            $res = response()->json([
                'status' => 'true',
                'run' => $run,
                'flag' => $post->flag,
                'message' => '승인이 취소되었습니다.',
            ], 200);

            return $res;
        }

        $run->countRun = $run->countRun - $req->run;
        $run->minusRun = $run->minusRun + $req->run;

        $run->save();

        $post->flag = true;

        $post-> save();

        $res = response()->json([
            'status' => 'success',
            'run' => $run,
            'post' => $post->flag
        ], 200);

        return $res;
    }
}