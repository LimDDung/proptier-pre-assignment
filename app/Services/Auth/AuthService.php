<?php

namespace App\Services\Auth;

use App\Repository\Auth\AuthRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Exception;

class AuthService
{
    public function __construct(private readonly AuthRepository $authRepository)
    {

    }


    /**
     * 로그인 로직 처리
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function signIn($request): array
    {

        try{

            $userId = $request->input("user_id");
            $password = $request->input("password");

            $userInfo = $this->authRepository->findByUserid($userId);
            //dd($userInfo); exit;
            if (!$userInfo ) {

                return [
                    "code"    => 500,
                    "message" => "존재하지 않는 아이디 입니다.",
                    "data"    => null,
                ];

            }
            else if(Hash::check($password, $userInfo->password) === false){ //
                return [
                    "code"    => 500,
                    "message" => "비밀번호가 올바르지 않습니다.",
                    "data"    => null,
                ];
            }

            $token = $userInfo->createToken('api');
            $accessTokenModel = $token->accessToken;
            $accessTokenModel->expires_at = now()->addMinutes(60); // 토큰만료 60분
            $accessTokenModel->save();

            return [
                "code"    => 200,
                "message" => "로그인 성공",
                "data"    =>  [
                    'token_type '=> 'Bearer',
                    'access_token' => $token->plainTextToken,
                    'expires_at'=> $accessTokenModel->expires_at->toIso8601String(),
                ],
            ];

        }catch (\Exception  $e){
            Log::error('[로그인 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "로그인 처리 중 오류가 발생했습니다.",
                "data"    => null,
            ];
        }

    }
}
