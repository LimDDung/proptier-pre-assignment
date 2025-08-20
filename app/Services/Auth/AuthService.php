<?php

namespace App\Services\Auth;

use App\Repository\Auth\AuthRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class AuthService
{
    public function __construct(private readonly AuthRepository $authRepository)
    {

    }


}
