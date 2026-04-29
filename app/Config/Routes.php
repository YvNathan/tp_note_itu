<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/login',   'AuthController::login');
$routes->post('/login',  'AuthController::doLogin');
$routes->get('/logout',  'AuthController::logout');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/list', 'EtudiantController::index');
$routes->get('/form', 'Home::form');

// Note routes
$routes->get('/note/form', 'NoteController::form');
$routes->get('/note/edit/(:num)', 'NoteController::edit/$1');
$routes->get('/note/getStudent/(:num)', 'NoteController::getStudent/$1');
$routes->get('/note/getCoursesBySemester/(:num)', 'NoteController::getCoursesBySemester/$1');
$routes->post('/note/save', 'NoteController::save');
$routes->get('/etudiants', 'EtudiantController::index');
$routes->get('/etudiants/(:num)/notes', 'NoteController::show/$1');
$routes->get('/etudiants/(:num)/matieres/(:num)', 'NoteController::history/$1/$2');
$routes->post('/note/update/(:num)', 'NoteController::update/$1');
$routes->post('/note/delete/(:num)', 'NoteController::delete/$1');
