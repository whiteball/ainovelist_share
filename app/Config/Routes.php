<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/register', 'Home::register');
$routes->post('/register', 'Home::register');
$routes->get('/login', 'Home::login');
$routes->post('/login', 'Home::login');
$routes->get('/config', 'Home::config');
$routes->post('/config', 'Home::config');
$routes->post('/logout', 'Home::logout');
$routes->get('/create/(:any)', 'Create::index/$1');
$routes->post('/create/(:any)', 'Create::index/$1');
$routes->get('/edit/(:num)', 'Create::edit/$1');
$routes->post('/edit/(:num)', 'Create::edit/$1');
$routes->post('/delete/(:num)', 'Create::delete/$1');
$routes->get('/prompt/(:num)', 'Home::prompt/$1');
$routes->get('/prompt_download/(:num)', 'Home::prompt/$1/1');
$routes->get('/tag/(:any)', 'Tag::index/$1');
$routes->get('/tags', 'Tag::list');
$routes->get('/search/tag', 'Tag::search');
$routes->get('/search/caption', 'Home::search');
$routes->get('/user/(:num)', 'User::index/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
