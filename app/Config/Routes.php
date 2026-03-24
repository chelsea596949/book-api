<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\BookPage;
use App\Controllers\UserPage;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// 不需要驗證的
$routes->get('users/login', [UserPage::class, 'login']);
$routes->post('api/login', 'Api\Auth::login');
$routes->get('api/books', 'Api\Books::getIndex');

// 需要驗證的
$routes->group('api', ['filter' => 'jwt'], function($routes) {
    // admin only
    $routes->group('', ['filter' => 'role:1'], function($routes) {
        $routes->post('books', 'Api\Books::create');
        $routes->delete('books/(:num)', 'Api\Books::deleteIndex/$1');
    });
});

// 需要驗證的
$routes->group('books', ['filter' => 'jwt'], function($routes) {
    $routes->get('new', [BookPage::class, 'new'], [
        'filter' => 'role:1'
    ]);
});