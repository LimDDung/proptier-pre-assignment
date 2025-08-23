<?php

namespace App\Services\User;
use App\Repository\User\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {

    }


    /**
     * 회원가입 로직 처리
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function createUser($request): array
    {

        try{

            DB::transaction(function () use ($request) {

                // 파라미터 정의
                $params = [
                    "userid"   => $request->input('user_id'),
                    "password" => $request->input('password'),
                    "name"     => $request->input('name'),
                    "email"    => $request->input('email'),
                ];

                // 생성
                $this->userRepository->usersCreate($params);

            });

            return [
                "code"    => 200,
                "message" => "회원가입이 완료되었습니다.",
                "data"    => null,
            ];

        }catch (\Exception  $e){

            Log::error('[회원가입 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "회원가입 처리 중 오류 발생",
                "data"    => null,
            ];
        }

    }

    /**
     * 회원정보 수정 로직 처리
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function updateUser($request)
    {
        try {

            $id = $request->user()->id;
            $params =[];
            if ($request->filled('name')) {
                $params['name'] = $request->input('name');
            }
            if ($request->filled('email')) {
                $params['email'] = $request->input('email');
            }
            if ($request->filled('password')) {
                $params['password'] = $request->input('password');
            }
            $user = $this->userRepository->userUpdateById($id , $params);

            return [
                "code"    => 200,
                "message" => "회원정보가 수정되었습니다.",
                "data"    => [
                    "id" => $user->id,
                ],
            ];

        }catch (\Exception $e){

            Log::error('[회원정보 수정 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "회원정보 수정중 오류 발생",
                "data"    => null,
            ];
        }
    }


    /**
     * 회원탈퇴 로직 처리
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function destroyUser($request)
    {

        try {

            $id = $request->user()->id;

            # 회원탈퇴
            $user = $this->userRepository->userDestroyById($id);

            # 인증토큰 삭제
            $request->user()->currentAccessToken()?->delete();

            return [
                "code"    => 200,
                "message" => "회원탈퇴가 완료되었습니다.",
                "data"    => null,
            ];

        }catch (\Exception $e){

            Log::error('[회원탈퇴 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "회원탈퇴중 오류 발생",
                "data"    => null,
            ];
        }

    }
}
