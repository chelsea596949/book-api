<?php

namespace App\Controllers;

class AdminPage extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Admin Panel',
        ];
        return view('templates/header', $data)
            . view('admin/sidebar')
            . view('admin/index')
            . view('admin/sidebar_end')
            . view('templates/footer');
    }
    
    public function booklist()
    {
        $data = [
            'title' => 'Book List',
            'page_js' => [
                'js/admin/booklist.js', 
                'js/api.js',
            ],
        ];
        return view('templates/header', $data)
            . view('admin/sidebar')
            . view('admin/booklist')
            . view('admin/sidebar_end')
            . view('templates/footer');
    }
}