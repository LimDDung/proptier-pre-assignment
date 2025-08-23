<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\User\UserController;
use App\Http\Controllers\V1\Board\BoardController;


Route::prefix('v1')->group(function () {


    #로그인 @@@@@@#@#@#@#@# 아이디 빌민번호 이셉션 잡으셈
    Route::post('/login', [AuthController::class, 'login']);

    #회원 API
    Route::prefix('users')->group(function () {
        Route::post('sign-up', [UserController::class, 'signUp']); //회원가입

    });

    #회원 Auth 인증 허용 API
    Route::middleware(['auth:sanctum'])->group(function () {

        #로그아웃
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('users')->group(function () {

            Route::put('me', [UserController::class, 'userModify']); //회원정보수정
            Route::delete('me', [UserController::class, 'userDestroy']); //회원탈퇴

        });

        #게시판 API
        Route::prefix('board')->group(function () {
            Route::post('/', [BoardController::class, 'boardCreate']); // 게시글생성
            Route::get('/', [BoardController::class, 'boardLists']); // 게시글 목록조회
            //Route::get('/{id}', [BoardController::class, 'asd']); // 게시글 상세조회
            //Route::put('/{id}', [BoardController::class, 'asd']); // 게시글 수정
            //Route::delete('/{id}', [BoardController::class, 'asd']); // 게시글 삭제
        });

    });

});
