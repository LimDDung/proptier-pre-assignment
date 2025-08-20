<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * 공통 API 응답 형식
     *
     * @param int $httpStatus HTTP 상태코드
     * @param string $bizCode 비즈니스 코드
     * @param mixed $message 응답 메시지
     * @param mixed|null $data 응답 데이터 (배열, 객체 등)
     * @return JsonResponse JSON 응답
     */
    protected function apiResponse(int $httpStatus ,string $bizCode = 'SUCCESS' , string|int $message=null, mixed $data = null): JsonResponse
    {
        return response()->json([
            'code' => $bizCode,         // 비즈니스 코드
            'message' => $message,
            'data' => $data,
        ], $httpStatus);               // HTTP 상태 코드
    }}
