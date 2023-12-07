<?php

namespace Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;

class AuthMiddleware
{
    public function handle(ServerRequest $request, \Closure $next)
    {
        if(isset($_SESSION['user-id'])){
            return $next($request);
        }
        else return new RedirectResponse('/login');
}
}