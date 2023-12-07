<?php

namespace Controllers;

use MiladRahimi\PhpRouter\View\View;

class MainController
{
    public function index(View $view)
    {
        return $view->make('index');
    }
    public function register(View $view)
    {
        return $view->make('auth.register');
    }

    public function login(View $view)
    {
        return $view->make('auth.login');
    }

    public function add_listing(View $view)
    {
        $categories = \ORM::for_table('categories')->findMany();
        return $view->make('profile.add-listing',[
            'categories' => $categories
        ]);
    }
}