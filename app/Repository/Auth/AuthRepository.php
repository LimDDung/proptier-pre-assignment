<?php

namespace App\Repository\Auth;

use App\Models\User;

class AuthRepository
{
    public function findByUserid(string $userId): ?User
    {
        return User::where('userid', $userId)->first();
    }
}
