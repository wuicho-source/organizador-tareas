<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('lang/(:segment)', 'Home::lang/$1');

$routes->match(['get', 'post'], 'login', 'Home::login');
$routes->match(['get', 'post'], 'signup', 'Home::signup');
$routes->get('activate/(:segment)', 'Home::activate/$1');
$routes->match(['get', 'post'], 'forgot-password', 'Home::forgotPassword');
$routes->get('reset-password/(:segment)', 'Home::resetPasswordForm/$1');
$routes->post('reset-password', 'Home::resetPassword');
$routes->get('logout', 'Home::logout');

$routes->get('profile', 'Profile::index');
$routes->match(['get', 'post'], 'profile/confirm-password', 'Profile::confirmPassword');
$routes->match(['get', 'post'], 'profile/edit', 'Profile::edit');
$routes->match(['get', 'post'], 'profile/change-password', 'Profile::changePassword');
$routes->match(['get', 'post'], 'profile/change-image', 'Profile::changeImage');
$routes->match(['get', 'post'], 'profile/delete-image', 'Profile::deleteImage');
$routes->match(['get', 'post'], 'profile/delete-account', 'Profile::deleteAccount');

$routes->get('users', 'Users::index');
$routes->match(['get', 'post'], 'users/create', 'Users::createUser');
$routes->get('users/edit/(:num)', 'Users::editUser/$1');
$routes->post('users/update/(:num)', 'Users::updateUser/$1');

$routes->get('tasks', 'Home::tasks');
$routes->get('tasks/show/(:num)', 'Home::showTask/$1');
$routes->post('tasks/upload-images/(:num)', 'Home::uploadTaskImages/$1');
$routes->get('tasks/delete-image/(:num)/(:num)', 'Home::deleteTaskImage/$1/$2');
$routes->get('tasks/create', 'Home::newTask');
$routes->post('tasks/create', 'Home::createTask');
$routes->get('tasks/edit/(:num)', 'Home::editTask/$1');
$routes->post('tasks/update/(:num)', 'Home::updateTask/$1');
$routes->get('tasks/toggle-hide/(:num)', 'Home::toggleHideTask/$1');
$routes->get('tasks/delete/(:num)', 'Home::deleteTask/$1');
