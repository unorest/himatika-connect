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

// Portal E-Sertifikat & Feedback Kegiatan
$routes->group('sertifikat', function ($routes) {
    $routes->get('/', 'CertificateController::index');
    $routes->post('submit', 'CertificateController::submit');
    $routes->get('sukses/(:segment)', 'CertificateController::sukses/$1');
    $routes->get('download/(:segment)', 'CertificateController::download/$1');
    $routes->get('preview/(:segment)', 'CertificateController::preview/$1');
    $routes->get('verify', 'CertificateController::verify');
    $routes->get('verify/(:segment)', 'CertificateController::verify/$1');
});

// Autentikasi Pengurus (Logout hanya via POST ber-CSRF untuk mencegah Logout CSRF)
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetLink');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->get('reset-password', 'Auth::resetPassword');
$routes->post('reset-password/(:any)', 'Auth::attemptResetPasswordWithToken/$1');
$routes->post('reset-password', 'Auth::attemptResetPasswordWithToken');
$routes->post('logout', 'Auth::logout');

// Area Internal Pengurus (Protected by AuthFilter)
$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // Kanban Task Tracker & Log Progress
    $routes->group('tasks', function ($routes) {
        $routes->get('/', 'TaskController::index');
        $routes->post('store', 'TaskController::store');
        $routes->post('program/store', 'TaskController::storeProgram');
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

    // Kelola Event & Workshop Publik (Kalender Beranda & E-Sertifikat)
    $routes->group('events', function ($routes) {
        $routes->get('/', 'EventManageController::index');
        $routes->post('store', 'EventManageController::store');
        $routes->post('update/(:num)', 'EventManageController::update/$1');
        $routes->post('delete/(:num)', 'EventManageController::delete/$1');
    });

    // Moderasi Aspirasi & Q&A Publik
    $routes->group('moderasi-qa', function ($routes) {
        $routes->get('/', 'ModerasiQAController::index');
        $routes->get('detail/(:num)', 'ModerasiQAController::detail/$1');
        $routes->post('answer', 'ModerasiQAController::answer');
        $routes->post('toggle-pin/(:num)', 'ModerasiQAController::togglePin/$1');
        $routes->post('delete/(:num)', 'ModerasiQAController::delete/$1');
        $routes->post('test-wa', 'ModerasiQAController::testWhatsApp');
        $routes->post('save-wa-settings', 'ModerasiQAController::saveWhatsAppSettings');
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

    // Kelola Pengurus & Role (Khusus BPH / Admin)
    $routes->group('pengurus', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/', 'PengurusController::index');
        $routes->post('store', 'PengurusController::store');
        $routes->post('update/(:num)', 'PengurusController::update/$1');
        $routes->post('toggle-status/(:num)', 'PengurusController::toggleStatus/$1');
        $routes->post('delete/(:num)', 'PengurusController::delete/$1');
    });

    // Rekap Feedback & E-Sertifikat
    $routes->get('certificates', 'CertificateController::adminIndex');

    // Profil & Ganti Kata Sandi (Untuk semua role: admin, kadiv, staff)
    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/password', 'ProfileController::updatePassword');
    $routes->post('profile/update', 'ProfileController::updateProfile');
    $routes->get('change-password', 'ProfileController::index');
});
