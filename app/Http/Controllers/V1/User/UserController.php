<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct(private readonly UserService $userService)
    {

    }

    /**
     * 회원가입
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function signUp(Request $request): JsonResponse
    {

        // 필수값 체크
        $validator = Validator::make($request->all(), [
            // 아이디: 12~20자, 영문 대소문자 + 특수문자 필수 포함
            'user_id' => [
                'required',
                'string',
                'min:12',
                'max:20',
                'regex:/^(?=.*[A-Za-z])(?=.*[^A-Za-z0-9]).{12,20}$/',
            ],

            // 비밀번호 : 암호화
            'password' => [
                'required',
                'string',
                'min:1',
                //'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
            ],

            // 이름
            'name' => [
                'required',
            ],
            // 이메일: 이메일 형식만 허용
            'email' => ['required', 'string', 'email', 'max:255'],

        ], [
            // 사용자 친화적 에러 메시지
            'user_id.required' => '아이디를 입력해주세요.',
            'user_id.min' => '아이디는 최소 12자 이상이어야 합니다.',
            'user_id.max' => '아이디는 최대 20자까지 가능합니다.',
            'user_id.regex' => '아이디는 영문과 특수문자를 반드시 포함해야 합니다.',

            'password.required' => '비밀번호를 입력해주세요.',
            'password.min' => '비밀번호는 최소 1자 이상이어야 합니다.',
            //'password.regex' => '비밀번호는 대문자, 소문자, 숫자, 특수문자를 모두 포함해야 합니다.',

            'name.required' => '이름을 입력해주세요.',

            'email.required' => '이메일을 입력해주세요.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 500, '입력값 검증 실패', $validator->errors());
        }

        $rt = $this->userService->createUser($request);

        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_CREATED, $code, $message, $data);

    }

    /**
     * 회원정보 수정
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function userModify(Request $request): JsonResponse
    {

        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name'     => ['sometimes','string','max:100'],
            'email'    => ['sometimes','email','max:255','unique:users,email,'.$user->id],
            'password' => ['sometimes','string','min:1'],
        ], [
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.unique' => '이미 사용 중인 이메일 주소입니다.',

        ]);

        if ($validator->fails()) {
            return $this->apiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 500, '입력값 검증 실패', $validator->errors());
        }

        $rt = $this->userService->updateUser($request);

        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_OK, $code, $message, $data);

    }


    /**
     * 회원탈퇴
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function userDestroy(Request $request): JsonResponse
    {

        $rt = $this->userService->destroyUser($request);

        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_OK, $code, $message, $data);
    }

}
