<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * 로그인
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function login(Request $request): JsonResponse
    {

        // 아이디 // 비밀번호 벨리데이션 체크
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'string', 'min:12', 'max:20'],
            'password' => ['required', 'string', 'min:1'],
        ], [
            'user_id.required' => '아이디를 입력해주세요.',
            'user_id.min' => '아이디는 12자 이상이어야 합니다.',
            'user_id.max' => '아이디는 20자 이하여야 합니다.',
            'password.required' => '비밀번호를 입력해주세요.',
            'password.min' => '비밀번호는 최소 1자 이상이어야 합니다.',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 500, '입력값 검증 실패', $validator->errors());
        }

        $rt = $this->authService->signIn($request);

        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_CREATED, $code, $message, $data);
    }


    /**
     * 로그가웃
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function logout(Request $request): JsonResponse
    {

        if (!$request->user() || !$request->user()->currentAccessToken()) {
            return $this->apiResponse(Response::HTTP_OK , 200, '이미 로그아웃 상태입니다.', null  );
        }

        $request->user()->currentAccessToken()?->delete();
        return $this->apiResponse(Response::HTTP_CREATED, 200, '로그아웃되었습니다.', null);
    }

}
