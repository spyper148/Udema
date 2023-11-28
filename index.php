<?php


use Controllers\MainController;
use Controllers\UserController;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use MiladRahimi\PhpContainer\Container;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\View\PhpView;
use MiladRahimi\PhpRouter\View\View;

require_once 'vendor/autoload.php';

session_start();

ORM::configure('mysql:host=localhost:3306;dbname=udema');
ORM::configure('username', 'root');
ORM::configure('password', '');

$router = Router::create();

$router->setupView('view');

$router->get('/', [MainController::class,'index'],'index');
$router->get('/register',[MainController::class,'register'],'register');
$router->post('/register',[UserController::class,'store'],'register');
$router->get('/login',[MainController::class,'login'],'login');
$router->post('/login',[UserController::class,'login'],'login');

$router->get('/add_listing',[MainController::class,'add_listing'],'add_listing');

try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    (new PhpView('view'))->make('404');
}