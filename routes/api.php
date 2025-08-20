<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\User\UserController;
use App\Http\Controllers\V1\Board\BoardController;


Route::prefix('v1')->group(function () {


    #로그인
    Route::post('/login', [AuthController::class, 'login']);

    #회원 Auth 인증 허용 API
    Route::middleware(['auth.token'])->group(function () {

        #로그아웃
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    });


    #회원 API
    Route::prefix('users')->group(function () {
        Route::post('sign-up', [UserController::class, 'signUp']); //회원가입
        Route::get('/', [UserController::class, 'index']); //회원조회
        #Route::get('/', [UserController::class, 'index']); //회원정보수정
        #Route::get('/', [UserController::class, 'index']); //회원탈퇴
        #Route::get('/', [UserController::class, 'index']); //회원가입
    });

    #게시판 API

    Route::prefix('board')->group(function () {
    });

});
