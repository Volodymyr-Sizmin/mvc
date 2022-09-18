<?php
/**
 * @var $router Core\Router
 */

use App\Controllers\AuthController;
use App\Controllers\UsersController;

$router->add('',[
        'controller'=> \App\Controllers\HomeController::class,
        'action' => 'index',
        'method' =>'GET']
);
$router->add('login',['controller'=>AuthController::class, 'action'=>'login', 'method'=>'GET']);
$router->add('logout',['controller'=>AuthController::class, 'action'=>'logout', 'method'=>'POST']);
$router->add('registration',['controller'=>AuthController::class, 'action'=>'registration', 'method'=>'GET']);
$router->add('auth/verify',['controller'=>AuthController::class, 'action'=>'verify', 'method'=>'POST']);
$router->add('users/store',['controller'=>UsersController::class, 'action'=>'store', 'method'=>'POST']);


//$router->add('admin/posts/{id:\d+}/edit',[
//    'controller'=> \App\Controllers\HomeController::class,
//    'action' => 'index',
//    'method' =>'GET']
//);
