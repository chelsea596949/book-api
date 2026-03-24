<?php
namespace App\Services;

class AuthService
{
    public function login(array $data)
    {
        $model = model('UserModel');

        $user = $model->where('uid', $data['uid'])->first();

        if(!$user) {
            return api_error('User not found', [], 404);
        }

        if(!password_verify($data['password'], $user['password'])) {
            return api_error('Password incorrect', [], 401);
        }

        $jwt = service('jwtService');

        $userInfo = [
            'uid' => $user['uid'],
            'level' => $user['level'],
        ];
        $token = $jwt->generate($userInfo);

        session()->set('jwt', $token);
        session()->set('userInfo', $userInfo);

        return api_success('', [
            'token' => $token
        ]);
    }
}