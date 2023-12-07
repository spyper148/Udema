<?php

namespace Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
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

    public function user_profile(View $view)
    {
        $user = \ORM::for_table('users')->where('id',$_SESSION['user-id'])->findOne();
        return $view->make('profile.user-profile',[
            'user' => $user
        ]);
    }

    public function teacher_profile(View $view)
    {
        $user = \ORM::for_table('users')->where('id',$_SESSION['user-id'])->findOne();

        $teacher = \ORM::for_table('teachers')
            ->where('user_id',$user->id)
            ->findOne();
            if($teacher){
                $courses = \ORM::for_table('courses')
                    ->where('teacher_id',$teacher->id)
                    ->join('categories', [
                        'categories.id'=>'category_id'
                    ])
                    ->findMany();
                return $view->make('profile.teacher-profile',[
                    "user"=>$user,
                    "teacher"=>$teacher,
                    "courses" => $courses
                    ]);
            }
            else
            {
                return new RedirectResponse('/user_profile');
            }

    }
}