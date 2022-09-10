<?php
/**
 * @var $router Core\Router
 */
$router->add('admin/posts/{id:\d+}/edit',[
    'controller'=> \APP\Controllers\HomeController::class,
    'action' => 'index',
    'method' =>'GET']
);
