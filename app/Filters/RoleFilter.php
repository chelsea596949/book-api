<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments=null)
    {
        $user = $request->user ?? null;

        if(!$user) {
            return api_response(
                service('response'),
                api_error('no user info', [], 401)
            );
        }

        if (!in_array($user['level'], $arguments)) {
            return api_response(
                service('response'),
                api_error('no permission', [], 403)
            );
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments=null)
    {
        // 不需要處理
    }
}