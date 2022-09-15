<?php

use Dotenv\Dotenv;

require_once dirname(__DIR__) . '/Config/constants.php';
require_once BASE_DIR . '/vendor/autoload.php';

$dotenv =Dotenv::createUnsafeImmutable(BASE_DIR);
$dotenv->load();

if (!session_id()){
    session_start();
}
//dd(\Core\Model::all()->orderBy('name', "ASC")->get());
//dd(\Core\Model::select());
//dd(\Core\Model::find(3));
//dd(\Core\Model::delete(5));
//dd(\Core\Model::findBy('age', "35")->get());
//dd(\Core\Model::findBy('age', "35"));
$fields= ['name'=>'Serhiy', 'surname'=>'Serhienko', 'age'=>'34'];;
$id = 11;
dd(\Core\Model::create($fields));
//dd(\Core\Model::update($fields, $id));
//dd(\Core\Model::where('age', '>',25 ));
try {
    $router = new \Core\Router();

    require_once BASE_DIR . '/Routes/web.php';

    if(!preg_match('/assets/i', $_SERVER['REQUEST_URI'])){
        $router->dispatch($_SERVER['REQUEST_URI']);
    }

} catch (Exception $e){
    dd($e->getMessage());
}

