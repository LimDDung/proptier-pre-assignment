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
}
