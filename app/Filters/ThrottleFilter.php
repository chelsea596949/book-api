<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments=null)
    {
        $throttler = service('throttler');

        // key 可以用 IP 或 user ID
        $key = md5($request->getIPAddress());

        $requestMax = env('throttle.requestMax', 10);
        if(!$throttler->check($key, $requestMax, MINUTE)) {
            return api_response(
                service('response'),
                api_error('Too Many Requests', [], 429)
            );
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments=null)
    {
        //
    }
}