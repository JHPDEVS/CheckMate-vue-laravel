<?php

use App\Http\Controllers\SocialController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return Inertia::render('Dashboard/Dashboard');
})->name('main');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard/Dashboard');
})->name('dashboard');

Route::get('/login/kakao',[SocialController::class,'redirect'])->name('kakao.login');

Route::get('/login/kakao/callback',[SocialController::class,'callback'])->name('kakaocall');
Route::middleware(['auth:sanctum', 'verified'])->get('/setInfo', function () {
    return Inertia::render('Auth/AddInfo');
})->name('setInfo');


Route::middleware(['auth:sanctum', 'verified','auth'])->get('/attend', function () {
    return Inertia::render('Menu/Attend');
})->name('attend');

Route::middleware(['auth:sanctum', 'verified','auth'])->get('/attendstatus', function () {
    return Inertia::render('Menu/AttendStatus');
})->name('attendstatus');

Route::post('setInfo/save', [SocialController::class, 'inputData'])->name('input');
Route::post('profile/save', [SocialController::class, 'profileEdit'])->name('profileEdit');
Route::middleware(['cors','auth:sanctum', 'verified','auth'])->group(function () {
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::get('/notice',function() {
        return Inertia::render('Board/Notice');
    })->name('notice');

    Route::get('/free',function() {
        return Inertia::render('Board/Free');
    })->name('free');

    Route::get('/freeshow/{post_id}',function() {
        return Inertia::render('Board/FreeShow');
    })->name('freeshow');

    Route::get('/freeedit/{post_id}',function() {
        return Inertia::render('Board/FreeEdit');
    })->name('freeedit');
    Route::get('/freewrite',function() {
        return Inertia::render('Board/FreeWrite');
    })->name('freewrite');
});

Route::middleware(['cors','auth:sanctum', 'verified','auth'])->group(function () {
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::get('/runauth/',function() {
        return Inertia::render('Board/RunAuth');
    })->name('runauth');

    Route::get('/runauth/show/{post_id}',function() {
        return Inertia::render('Board/Show');
    })->name('runauthshow');

    Route::get('/write',function() {
        return Inertia::render('Board/Write');
    })->name('runauthwrite');

    Route::get('/edit/{post_id}',function() {
        return Inertia::render('Board/Edit');
    })->name('runauthedit');

    Route::get('/classAttendStatus',function() {
        return Inertia::render('Menu/ClassAttendStatus');
    })->name('classAttendStatus');
});

Route::get('/timetable',function() {
    return Inertia::render('TimeTable/TimeTable');
})->name('timetable');

Route::middleware(['auth:sanctum', 'verified'])
            ->get('/dashboard/professor',function(){
                return Inertia::render('Professor/DashboardProfessor');
            })
            ->name('dashboard.professor');