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

    public function reset_password(ServerRequest $request):RedirectResponse
    {
        $person = \ORM::for_table('users')->where('id',$_SESSION['user-id'])->findOne();
        $params = $request->getParsedBody();
        if(password_verify($params['old_password'],$person->password) && $params['new_password'] == $params['conf_password']){
            $person->password = password_hash($params['new_password'],PASSWORD_DEFAULT);
            $person->save();
            return new RedirectResponse('/');
        }
        else return new RedirectResponse('/user_profile');

    }

    public function reset_email(ServerRequest $request):RedirectResponse
    {
        $person = \ORM::for_table('users')->where('id',$_SESSION['user-id'])->findOne();
        $params = $request->getParsedBody();
        if($params['old_email'] == $person->email ){
            $person->email = $params['new_email'];
            $person->save();
            return new RedirectResponse('/');
        }
        else return new RedirectResponse('/user_profile');
    }
}