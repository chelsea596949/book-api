<?php

namespace App\Controllers;

class BookPage extends BaseController
{
    // public function new()
    // {
    //     helper('form');

    //     $data = [
    //         'title' => 'Create a book item',
    //     ];

    //     return view('templates/header', $data)
    //         . view('books/create')
    //         . view('templates/footer');
    // }

    public function index()
    {
        $data = [
            'title' => 'That Bookstore.',
            'page_css' => [
                'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
                'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css',
            ],
            'page_js' => [
                'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
                'js/shared-header.js',
                'js/home.js', 
                'js/api.js',
            ],
        ];
        return view('templates/header', $data)
            . view('books/index')
            . view('templates/footer');
    }

    public function detail()
    {
        $data = [
            'page_js' => [
                'js/shared-header.js',
                'js/detail.js', 
                'js/api.js',
            ],
        ];
        return view('templates/header', $data)
            . view('books/detail')
            . view('templates/footer');
    }

    public function display()
    {
        $data = [
            'title' => 'Books Collection',
            'page_css' => [
                'book-display.css',
            ],
            'page_js' => [
                'js/shared-header.js',
                'js/book-display.js',
                'js/api.js',
            ],
        ];
        return view('templates/header', $data)
            . view('books/display')
            . view('templates/footer');
    }
}