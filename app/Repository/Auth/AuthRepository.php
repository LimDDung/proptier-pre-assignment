<?php

namespace App\Repository\Auth;

use App\Models\User;

class AuthRepository
{
    public function findByUserid(string $userId)
    {
        try {
            return User::where('userid', $userId)->first();
        }catch (\Throwable $e){
            throw $e;
        }
    }
}
