<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/login', 'Home::login');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/list', 'Home::list');
$routes->get('/form', 'Home::form');

// Note routes
$routes->get('/note/form', 'NoteController::form');
$routes->get('/note/getStudent/(:num)', 'NoteController::getStudent/$1');
$routes->get('/note/getCoursesBySemester/(:num)', 'NoteController::getCoursesBySemester/$1');
$routes->post('/note/save', 'NoteController::save');
