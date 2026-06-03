<?php
namespace App\Services;

use App\DTO\User\UserRegistrationDTO;

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
        $token_res = $jwt->generate($userInfo);
        $token = $token_res['token'];
        $iat = $token_res['iat'];
        $exp = $token_res['exp'];

        // session()->set('jwt', $token);
        // session()->set('userInfo', $userInfo);

        return api_success('', [
            'uid' => $user['uid'],
            'token' => $token,
            'iat' => $iat,
            'exp' => $exp
        ]);
    }

    public function register(array $data)
    {
        $dto = UserRegistrationDTO::fromArray($data);

        $model = model('UserModel');

        // Create new user with default level 2
        $newUser = [
            'uid' => $dto->uid,
            'password' => password_hash($dto->password, PASSWORD_BCRYPT),
            'name' => $dto->name,
            'level' => 2,
        ];

        $result = $model->insert($newUser);

        if($result) {
            return api_success('User registered successfully', [
                'uid' => $dto->uid
            ]);
        } else {
            return api_error('Failed to register user', [], 500);
        }
    }

    public function listMembers(?int $page, ?int $perPage): array
    {
        $model = model('UserModel');
        $model->where('level', 2)
            ->select('uid, name, level, created_at, updated_at');

        if($page !== null && $perPage !== null) {
            $users = $model->paginate($perPage, 'default', $page);
            $meta = api_pagination($model->pager, $perPage);

            return api_success('', $users, ['pagination' => $meta]);
        }

        $users = $model->findAll();

        return api_success('', $users);
    }

    public function deleteUser(string $uid)
    {
        $model = model('UserModel');

        $user = $model->where('uid', $uid)->first();

        if(!$user) {
            return api_error('User not found', [], 404);
        }

        $result = $model->delete($uid);

        if($result) {
            return api_success('User deleted successfully', []);
        } else {
            return api_error('Failed to delete user', [], 500);
        }
    }
}