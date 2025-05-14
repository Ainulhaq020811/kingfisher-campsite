<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------------
// Router Setup
// --------------------------------------------------------------------
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// If you don’t want auto-routing, set this to false:
// $routes->setAutoRoute(false);

// --------------------------------------------------------------------
// Public (Web) Routes
// --------------------------------------------------------------------

// Authentication
$routes->get('/',              'Auth::base');
$routes->get('auth/login',     'Auth::index');
$routes->get('auth/register',  'Auth::register');
$routes->post('auth/save',     'Auth::save');
$routes->post('auth/check',    'Auth::check');
$routes->get('auth/logout',    'Auth::logout');

// Static Pages
$routes->get('/pages/home',            'Home::index');
$routes->post('/pages/home',           'Home::handlePost');
$routes->get('/our-story',             'OurStoryController::index');
$routes->get('/our-story/milestones',  'OurStoryController::milestones');
$routes->get('/our-story/gallery',     'OurStoryController::gallery');
$routes->get('/our-story/faq',         'OurStoryController::faq');

// Booking UI
$routes->get('book-online',            'Booking::index');
$routes->post('book-online/submit',    'Booking::submit', ['filter' => 'authGuard']);

// Camp-related UI
$routes->post('/Campsite/booking',              'CampBookingController::create',          ['filter' => 'AuthCheck']);
$routes->get('/Campsite/admin',                 'CampAdminController::index',             ['filter' => 'AuthCheck']);
$routes->post('Campsite/admin/campsite/update', 'CampAdminController::updateCampsite',    ['filter' => 'AuthCheck']);

// --------------------------------------------------------------------
// Authenticated (Web) Routes
// --------------------------------------------------------------------
$routes->group('', ['filter' => 'AuthCheck'], function($routes) {
    $routes->get('dashboard',                    'Dashboard::index');
    $routes->get('dashboard/profile',            'Dashboard::profile');

    // Users management
    $routes->get('dashboard/read_users',         'Users::index');
    $routes->get('dashboard/dashboard/update_form/(:num)', 'Users::update_form/$1');
    $routes->put('dashboard/read_users/(:num)',  'Users::update/$1');
    $routes->get('dashboard/users',              'Users::index');
    $routes->get('dashboard/new_user',           'Users::new');
    $routes->post('dashboard/read_users',        'Users::create');
    $routes->post('dashboard/delete_users/(:num)','Users::delete_user/$1');

    // Our Story 2 (protected pages)
    $routes->get('our-story2',                   'OurStory2Controller::index2');
    $routes->get('our-story2/milestones2',       'OurStory2Controller::milestones2');
    $routes->get('our-story2/gallery2',          'OurStory2Controller::gallery2');
    $routes->get('our-story2/faq2',              'OurStory2Controller::faq2');
});

// --------------------------------------------------------------------
// Already-Logged-In Filter (prevent access to login/register)
// --------------------------------------------------------------------
$routes->group('', ['filter' => 'AlreadyLoggedIn'], function($routes) {
    $routes->get('auth',            'Auth::index');
    $routes->get('auth/register',   'Auth::register');
});

// --------------------------------------------------------------------
// API Routes (RESTful Resources)
// --------------------------------------------------------------------
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($r) {
    $r->resource('booking',  ['controller' => 'BookingController']);
    $r->resource('customer', ['controller' => 'CustomerController']);
    $r->resource('lot',      ['controller' => 'LotController']);
    $r->resource('rental',   ['controller' => 'RentalController']);
    $r->resource('zone',     ['controller' => 'ZoneController']);
    // If you need a custom read-only endpoint for your view:
    // $r->get('ordered-users', 'Reports::orderedUsers');
});

// --------------------------------------------------------------------
// Additional Routing (if environment-specific overrides exist)
// --------------------------------------------------------------------
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
