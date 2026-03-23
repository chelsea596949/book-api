<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        $data = $this->request->getPost();

        $service = service('authService');
        $result = $service->login($data);

        return api_response($this->response, $result);
    }
}