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

}
