<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/login',   'AuthController::login');
$routes->post('/login',  'AuthController::doLogin');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/list', 'Etudiant::index');
$routes->get('/form', 'Home::form');
$routes->get('/etudiants', 'Etudiant::index');
$routes->get('/etudiant/notes/(:num)', 'Etudiant::notes/$1');
$routes->get('/etudiants/(:num)/notes', 'Etudiant::notes/$1');


