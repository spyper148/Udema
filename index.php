<?php


use Controllers\CourseController;
use Controllers\MainController;
use Controllers\UserController;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Middleware\AuthMiddleware;
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
$router->get('/courses',[CourseController::class,'list'],'courses');
$router->get('/course',[MainController::class,'course_detail'],'course');

$router->group(['middleware'=>[AuthMiddleware::class]],function(Router $router){
    $router->get('/add_listing',[MainController::class,'add_listing'],'add_listing');
    $router->post('/add_listing_store',[CourseController::class, 'store'],'add_listing_store');
    $router->get('/user_profile',[MainController::class,'user_profile'],'user_profile');
    $router->post('/reset_password',[UserController::class,'reset_password'],'reset_password');
    $router->post('/reset_email',[UserController::class,'reset_email'],'reset_email');
    $router->get('/teacher_profile',[MainController::class,'teacher_profile'],'teacher_profile');

    $router->get('/clear-session',function (): RedirectResponse
    {
        unset($_SESSION['user-id']);
        return new RedirectResponse('/');
    });
});

try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    (new PhpView('view'))->make('404');
}
