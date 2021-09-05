<?php

namespace App\Http\Controllers;

use App\Models\Attend_comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Attend_commentsController extends Controller
{
    // 댓글 쓰기
    public function create(Request $req, $postId) {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $comment = new Attend_comments();
        $comment->user_id = $req->user_id;
        $comment->attend_post_id = $postId;
        $comment->content = $req->content;

        $comment->save();

        return response()->json([
            'status' => 'success',
            'comment' => $comment,
        ], 200);
    }

    // 댓글 삭제
    public function destroy(Attend_comments $comment) {
        $comment->delete();

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    // 댓글 수정
    public function update(Request $req, Attend_comments $comment) {
        $validator = Validator::make($req->all(), [
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $comment->content = $req->content;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'comment' => $comment,
        ], 200);
    }
}