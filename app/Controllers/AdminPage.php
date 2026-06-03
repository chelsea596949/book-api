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
                'js/api.js',
                'js/admin/booklist.js',
            ],
        ];
        return view('templates/header', $data)
            . view('admin/sidebar')
            . view('admin/booklist')
            . view('admin/sidebar_end')
            . view('templates/footer');
    }

    public function userlist()
    {
        $data = [
            'title' => 'Member Management',
            'is_admin_page' => true,
            'page_js' => [
                'js/api.js',
                'js/admin/userlist.js',
            ],
        ];
        return view('templates/header', $data)
            . view('admin/sidebar')
            . view('admin/userlist')
            . view('admin/sidebar_end')
            . view('templates/footer');
    }
}