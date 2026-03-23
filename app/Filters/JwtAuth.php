<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\ExpiredException;

class JwtAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments=null)
    {
        $token = null;

        // 先抓 Header（API 用）
        $header = $request->getHeaderLine('Authorization');
        if($header && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        }

        // 再抓 Session（頁面用）
        if(!$token) {
            $token = session()->get('jwt');
        }

        // 都沒有 token
        if(!$token) {
            return api_response(
                service('response'),
                api_error('Token not provided', [], 401)
            );
        }

        try {
            // 驗證 JWT
            $jwt = service('jwtService');
            $decoded = $jwt->verify($token);

            // 塞 user 到 request
            $request->user = (array)$decoded->data;

        }catch (ExpiredException $e) {
            return api_response(
                service('response'),
                api_error('Token expired', [], 401)
            );

        }catch (\Exception $e) {
            return api_response(
                service('response'),
                api_error('Invalid token', [], 401)
            );
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments=null)
    {
        // 不需要處理
    }
}