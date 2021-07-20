<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['web']], function () {
    Route::middleware(['cors'])->group(function () {
        Route::get('/csrf_token', function(){
            return csrf_token();
        });
        Route::get('/login/kakao',[SocialController::class,'redirect'])->name('kakao');
    });
});



Route::get('/login/kakao/callback',[SocialController::class,'callback'])->name('kakaocall');