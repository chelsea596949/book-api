<?php

namespace App\Controllers;

class AdminPage extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Admin Panel',
            'is_admin_page' => true,
        ];
        return view('templates/header', $data)
            . view('admin/sidebar')
            . view('admin/index')
            . view('admin/sidebar_end')
            . view('templates/footer');
    }
    
    public function booklist()
    {
        helper('form');

        $data = [
            'title' => 'Book List',
            'is_admin_page' => true,
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