<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home and About pages
$routes->get('/', 'Pages::index');
$routes->get('about', 'Pages::about');

// TFA4: login / logout (public). Logout is POST so it needs the CSRF token.
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::attempt');
$routes->post('logout', 'Auth::logout');

// TFA4: everything inside this group runs the "auth" filter first, so a
// visitor who is not logged in is sent to /login before any controller runs.
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    // Customer Accounts
    $routes->get('customers', 'Customers::index');
    $routes->get('customers/new', 'Customers::new');
    $routes->post('customers/create', 'Customers::create');
    $routes->get('customers/edit/(:num)', 'Customers::edit/$1');
    $routes->post('customers/update/(:num)', 'Customers::update/$1');

    // User Accounts
    $routes->get('users', 'Users::index');
    $routes->get('users/new', 'Users::new');
    $routes->post('users/create', 'Users::create');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
});
