<?php

namespace App\Controllers;

class BookPage extends BaseController
{
    public function new()
    {
        helper('form');

        return view('templates/header', ['title' => 'Create a books item'])
            . view('books/create')
            . view('templates/footer');
    }
}