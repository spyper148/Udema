<?php

namespace Controllers;

use Classes\Auth;
use Couchbase\Origin;
use Couchbase\View;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpContainer\Tests\Classes\A;


class UserController
{
    public function store(ServerRequest $request):RedirectResponse
    {
        $params = $request->getParsedBody();

        $person = \ORM::for_table('users')->create();
        $person->first_name = $params['first_name'];
        $person->last_name = $params['last_name'];
        $person->email = $params['email'];
        $person->password = password_hash($params['password'],PASSWORD_DEFAULT);
        $person->admin = 0;
        $person->save();


        Auth::login($person->id);

        return new RedirectResponse('/');
    }

    public function login(ServerRequest $request):RedirectResponse
    {
        $params = $request->getParsedBody();
        $person = \ORM::for_table('users')->where('email',$params['email'])->findOne();

        if($person && password_verify($params['password'],$person->password)){
            Auth::login($person->id);
            return new RedirectResponse('/');
        }
        else{
            return new RedirectResponse('/login');
        }

    }

}