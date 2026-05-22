<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\DTO\User\RegistrationDTO;

class Auth extends BaseController
{
    public function login()
    {
        $data = $this->request->getPost();

        $service = service('authService');
        $result = $service->login($data);

        return api_response($this->response, $result);
    }

    public function register()
    {
        $data = $this->request->getPost();

        if(!$this->validate(RegistrationDTO::rules())) {
            return api_response(
                $this->response,
                api_error(
                    'Validation failed',
                    $this->validator->getErrors(),
                    400
                )
            );
        }

        $service = service('authService');
        $result = $service->register($data);

        return api_response($this->response, $result);
    }
}