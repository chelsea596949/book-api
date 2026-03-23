<?php

namespace App\Controllers;

class UserPage extends BaseController
{
    public function login()
    {
        helper('form');

        return view('templates/header', ['title' => 'Login'])
            . view('users/login')
            . view('templates/footer');
    }
}