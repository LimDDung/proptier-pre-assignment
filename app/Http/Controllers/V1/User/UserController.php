<?php

namespace App\Http\Controllers\V1\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\User\UserService;

class UserController extends Controller
{

    public function __construct(UserService $userService)
    {

    }

    /**
     * 회원가입
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 로그인 성공/실패 응답
     */
    public function signUp(Request $request)
    {
        echo 123;
        exit;
        //return response()->json($comment, Response::HTTP_CREATED);
    }

}
