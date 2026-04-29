<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/login',   'AuthController::login');
$routes->post('/login',  'AuthController::doLogin');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/list', 'EtudiantController::index');
$routes->get('/form', 'Home::form');
$routes->get('/etudiants', 'EtudiantController::index');
$routes->get('/etudiant/notes/(:num)', 'EtudiantController::notes/$1');
$routes->get('/etudiants/(:num)/notes', 'EtudiantController::notes/$1');
