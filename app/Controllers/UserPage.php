<?php

namespace App\Controllers;

class UserPage extends BaseController
{
    public function login()
    {
        helper('form');

        $data = [
            'title' => 'Login',
            'page_js' => [
                'js/login.js', 
                'js/api.js',
            ],
        ];

        return view('templates/header', $data)
            . view('users/login')
            . view('templates/footer');
    }
}