<?php

namespace App\Repository\User;
use App\Models\User;

class UserRepository
{

    /**
     * 회원 생성
     *
     * @param array $params 파라미터
     * @return object
     * @throws \Exception 예외 발생 시 처리 (필요 시 throw 가능)
     */
    public function usersCreate(array $params)
    {
        try {

            return User::create($params)->id;

        }catch (\Throwable $e){
            throw $e;
        }

    }
}
