<?php

namespace App\Http\Controllers;

use App\Models\Attend_comments;
use App\Models\Attend_posts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Attend_postsController extends Controller
{
    public function uploadPostImage($req) {
        // 이름에 시간 넣기
        $name = $req->file('imageFile')->getClientOriginalName();
        $extension = $req->file('imageFile')->extension();
        $nameWithoutExtension = Str::of($name)->basename('.' . $extension);
        $fileName = $nameWithoutExtension . '_' . time() . '.' . $extension;

        // 파일의 경로와 이름 지정
        $req->file('imageFile')->storeAs('public/images', $fileName);

        return $fileName;
    }

    // post 생성
    public function create(Request $req) {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer',
            'content' => 'required|string',
            'run' => 'required|integer',
            'imageFile' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        $post = new Attend_posts();
        $post->user_id = $req->user_id;
        $post->content = $req->content;
        $post->run = $req->run;

        if ($req->file('imageFile')) {
            $post->image = $this->uploadPostImage($req);
        }

        $post->save();

        $res = response()->json([
            'status' => 'success',
            'post' => $post,
        ], 200);

        return $res;
    }

    // post 전체 보여주기
    public function index() {
        $posts = DB::table('attend_posts')
        ->join('users', 'users.id', '=', 'attend_posts.user_id')
        ->select(
            DB::raw("DATE_FORMAT(attend_posts.created_at, '%Y-%m-%d') as date"),
            DB::raw("DATE_FORMAT(attend_posts.updated_at, '%Y-%m-%d') as updated_date"),
            DB::raw('attend_posts.id, attend_posts.content,
            attend_posts.user_id, attend_posts.image, attend_posts.flag,
            attend_posts.updated_at, attend_posts.run, users.name'),
        )->orderBy('attend_posts.id', 'desc')->paginate(10);

        $commentsCount = DB::table('attend_comments')
        ->join('attend_posts', 'attend_posts.id', '=', 'attend_comments.attend_post_id')
        ->select(
            DB::raw('attend_posts.id, COUNT(attend_comments.id) as count')
        )->groupBy('attend_posts.id')->orderBy('attend_posts.created_at', 'desc')
        ->get();


        foreach($posts as $row) {
            $flag = true;

            for ($i = 0; $i < $commentsCount->count(); $i++) {
                if ($row->id == $commentsCount[$i]->id) {
                    $row->comments_count = $commentsCount[$i]->count;
                    $flag = false;
                    break;
                }
            }

            if ($flag) {
                $row->comments_count = null;
            }
        }

        $res = response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);

        return $res;
    }

    // post 상세보기
    public function show($selected_post_id) {
        $post = DB::table('attend_posts')
        ->join('users', 'users.id', '=', 'attend_posts.user_id')
        ->where('attend_posts.id', '=', $selected_post_id)
        ->select(
            DB::raw("DATE_FORMAT(attend_posts.created_at, '%Y-%m-%d %T') as date"),
            DB::raw('attend_posts.id, attend_posts.content,
            attend_posts.user_id, attend_posts.image, attend_posts.flag,
            attend_posts.updated_at, attend_posts.run, users.name, users.profile_photo_path'),
        )
        ->get();

        $comment = DB::table('attend_comments')
        ->join('users', 'users.id', '=', 'attend_comments.user_id')
        ->where('attend_post_id', '=', $selected_post_id)
        ->select(
            DB::raw('attend_comments.id, attend_comments.content, users.name, attend_comments.attend_post_id,
            attend_comments.created_at, attend_comments.updated_at, attend_comments.user_id,
            users.profile_photo_path')
        )
        ->orderBy('attend_comments.id', 'desc')->paginate(10);

        foreach($comment as $row) {
            $row->updated_at = Carbon::parse($row->updated_at);
            $row->updated_at = $row->updated_at->diffForHumans(Carbon::now());
        }

        $res = response()->json([
            'status' => 'success',
            'post' => $post,
            'comment' => $comment,
        ]);

        return $res;
    }

      // post 수정
      public function update(Request $req, $selected_post_id) {


        $post = Attend_posts::find($selected_post_id);
        if(($req->imageFile == $post->image)) {
            $validator = Validator::make($req->all(), [
                'user_id' => 'required|integer',
                'content' => 'required|string',
                'run' => 'integer'
            ]);
            $req->imageFile = $post->image;
        } else {
            $validator = Validator::make($req->all(), [
                'user_id' => 'required|integer',
                'content' => 'required|string',
                'imageFile' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'run' => 'integer'
            ]);
        }

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        if ($req->user_id != $post->user_id) {
            $res = response()->json([
                'status' => 'false',
                'message' => '작성자가 아닙니다.',
            ], 200, [], JSON_UNESCAPED_UNICODE);

            return $res;
        }

        if ($req->file('imageFile')) {
            $imagePath = 'public/images/' . $post->image;
            Storage::delete($imagePath);
            $post->image = $this->uploadPostImage($req);
        } else {
            $imagePath = 'public/images/' . $post->image;
        }

        $post->content = $req->content;
        if ($req->run != null) {
            $post->run = $req->run;
        }

        $post->save();

        $res = response()->json([
            'status' => 'success',
            'post' => $post,
        ], 200);

        return $res;
    }

    public function destroy(Request $req, $selected_post_id) {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        $post = Attend_posts::find($selected_post_id);

        if ($req->user_id != $post->user_id) {
            $res = response()->json([
                'status' => 'false',
                'message' => '작성자가 아닙니다.',
            ], 200, [], JSON_UNESCAPED_UNICODE);

            return $res;
        }

        if ($post->image) {
            $imagePath = 'public/images/' . $post->image;
            Storage::delete($imagePath);
        }

        $post->delete();

        $res = response()->json([
            'status' => 'ture',
            'message' => '삭제가 되었습니다.',
        ], 200, [], JSON_UNESCAPED_UNICODE);

        return $res;
    }

    // post 검색
    public function search($searched_user_name) {
        $posts = DB::table('attend_posts')
        ->join('users', 'users.id', '=', 'attend_posts.user_id')
        ->where('users.name', 'like', '%'.$searched_user_name.'%')
        ->select(
            DB::raw("DATE_FORMAT(attend_posts.created_at, '%Y-%m-%d') as date"),
            DB::raw("DATE_FORMAT(attend_posts.updated_at, '%Y-%m-%d') as updated_date"),
            DB::raw('attend_posts.id, attend_posts.content,
            attend_posts.user_id, attend_posts.image, attend_posts.flag,
            attend_posts.updated_at, attend_posts.run, users.name'),
        )->orderBy('attend_posts.id', 'desc')->paginate(10);

        $commentsCount = DB::table('attend_comments')
        ->join('attend_posts', 'attend_posts.id', '=', 'attend_comments.attend_post_id')
        ->select(
            DB::raw('attend_posts.id, COUNT(attend_comments.id) as count')
        )->groupBy('attend_posts.id')->orderBy('attend_posts.created_at', 'desc')
        ->get();

        foreach($posts as $row) {
            $flag = true;
            for ($j = 0; $j < $commentsCount->count(); $j++) {
                if ($row->id == $commentsCount[$j]->id) {
                    $row->comments_count = $commentsCount[$j]->count;
                    $flag = false;
                    break;
                }
            }

            if ($flag) {
                $row->comments_count = null;
            }
        }

        $res = response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);

        return $res;
    }
}