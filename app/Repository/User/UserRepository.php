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


    /**
     * 회원 수정
     *
     * @param int $id 유저pk
     * @param array $params 파라미터
     * @return object
     * @throws \Exception 예외 발생 시 처리 (필요 시 throw 가능)
     */
    public function userUpdateById($id, array $params)
    {
        try {
            $user = User::findOrFail($id);
            if(!empty($params['name'])){
                $user->name = $params['name'];
            }
            if(!empty($params['email'])){
                $user->email = $params['email'];
            }
            if(!empty($params['password'])){
                $user->password = $params['password'];
            }
            $user->save();

            return $user;

        }catch (\Throwable $e){
            throw $e;
        }
    }


    /**
     * 회원탈퇴
     *
     * @param int $id 유저pk
     * @return object
     * @throws \Exception 예외 발생 시 처리 (필요 시 throw 가능)
     */
    public function userDestroyById($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return $user;

        }catch (\Throwable $e){
            throw $e;
        }
    }
}
