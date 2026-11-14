<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Halaman Publik
$routes->get('/', 'Home::index');

// Warta & Berita Publik
$routes->group('berita', function ($routes) {
    $routes->get('/', 'Blog::index');
    $routes->get('(:segment)', 'Blog::detail/$1');
});

// Forum Aspirasi & Q&A Publik
$routes->group('forum', function ($routes) {
    $routes->get('/', 'Forum::index');
    $routes->post('store', 'Forum::store');
    $routes->get('detail/(:num)', 'Forum::detail/$1');
    $routes->post('upvote/(:num)', 'Forum::upvote/$1');
});

// Autentikasi Pengurus
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
$routes->post('logout', 'Auth::logout');

// Area Internal Pengurus (Protected by AuthFilter)
$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // Kanban Task Tracker & Log Progress
    $routes->group('tasks', function ($routes) {
        $routes->get('/', 'TaskController::index');
        $routes->post('store', 'TaskController::store');
        $routes->post('update-status', 'TaskController::updateStatus');
        $routes->post('add-log', 'TaskController::addProgressLog');
    });

    // Agenda & Rapat Internal
    $routes->group('schedules', function ($routes) {
        $routes->get('/', 'ScheduleController::index');
        $routes->post('store', 'ScheduleController::store');
        $routes->post('rsvp', 'ScheduleController::rsvp');
        $routes->post('delete/(:num)', 'ScheduleController::delete/$1');
    });

    // Moderasi Aspirasi & Q&A Publik
    $routes->group('moderasi-qa', function ($routes) {
        $routes->get('/', 'ModerasiQAController::index');
        $routes->get('detail/(:num)', 'ModerasiQAController::detail/$1');
        $routes->post('answer', 'ModerasiQAController::answer');
        $routes->post('toggle-pin/(:num)', 'ModerasiQAController::togglePin/$1');
        $routes->post('delete/(:num)', 'ModerasiQAController::delete/$1');
    });

    // Kelola Berita & Artikel
    $routes->group('posts', function ($routes) {
        $routes->get('/', 'PostManageController::index');
        $routes->get('create', 'PostManageController::create');
        $routes->post('store', 'PostManageController::store');
        $routes->get('edit/(:num)', 'PostManageController::edit/$1');
        $routes->post('update/(:num)', 'PostManageController::update/$1');
        $routes->post('delete/(:num)', 'PostManageController::delete/$1');
    });
});
