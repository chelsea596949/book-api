<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\BookPage;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('books/new', [BookPage::class, 'new']);
