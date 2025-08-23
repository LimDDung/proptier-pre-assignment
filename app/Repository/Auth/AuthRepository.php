<?php

namespace App\Repository\Auth;

use App\Models\User;

class AuthRepository
{


    /**
     * 유저조회
     *
     * @param string $userId 사용자ID
     * @return object
     * @throws \Throwable
     */
    public function findByUserid(string $userId)
    {
        try {
            return User::where('userid', $userId)->first();
        }catch (\Throwable $e){
            throw $e;
        }
    }
}
