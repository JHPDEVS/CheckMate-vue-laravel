<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetablesController extends Controller
{

    // 시간표 가져오기
    public function getTimetables() {
        $timetables = Timetable::all();

        $res = response()->json([
            'status' => 'success',
            'data' => $timetables
        ], 200,[], JSON_UNESCAPED_UNICODE);

        return $res;
    }
}