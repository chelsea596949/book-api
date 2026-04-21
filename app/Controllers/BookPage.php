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
            // 定義這頁專用的 CSS
            'page_css' => [
                'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
                'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css',
            ],
            'page_js' => [
                'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
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
                'js/detail.js', 
                'js/api.js',
            ],
        ];
        return view('templates/header', $data)
            . view('books/detail')
            . view('templates/footer');
    }
}