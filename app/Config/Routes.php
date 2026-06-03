<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\BookPage;
use App\Controllers\UserPage;
use App\Controllers\AdminPage;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'BookPage::index');
$routes->get('books/display', 'BookPage::display');
$routes->get('books/detail/(:num)', 'BookPage::detail/$1');

// 不需要驗證的
$routes->get('users/login', [UserPage::class, 'login']);
$routes->get('users/register', [UserPage::class, 'register']);
$routes->post('api/login', 'Api\Auth::login');
$routes->post('api/register', 'Api\Auth::register');
$routes->get('api/books', 'Api\Books::getIndex');
$routes->get('api/books/(:num)', 'Api\Books::getIndex/$1');
$routes->group('api', ['filter' => ['jwt', 'throttle']], function($routes) {
    // admin only
    $routes->group('', ['filter' => 'role:1'], function($routes) {
        $routes->get('users', 'Api\Auth::index');
        $routes->delete('users/(:any)', 'Api\Auth::delete/$1');
        $routes->post('books', 'Api\Books::postIndex');
        $routes->put('books/(:num)', 'Api\Books::putIndex/$1');
        $routes->delete('books/(:num)', 'Api\Books::deleteIndex/$1');
    });
});

// 需要驗證的
// $routes->group('books', ['filter' => 'jwt'], function($routes) {
//     $routes->get('new', [BookPage::class, 'new'], [
//         'filter' => 'role:1'
//     ]);
// });
$routes->group('admin', ['filter' => ['jwt', 'role:1']], function($routes) {
    $routes->get('', [AdminPage::class, 'index']);
    $routes->get('index', [AdminPage::class, 'index']);
    $routes->get('booklist', [AdminPage::class, 'booklist']);
    $routes->get('userlist', [AdminPage::class, 'userlist']);
});