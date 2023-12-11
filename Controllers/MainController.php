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
                    ->raw_query("SELECT courses.name, courses.description,courses.price,categories.category FROM courses INNER JOIN categories ON categories.id = courses.category_id WHERE courses.teacher_id = $teacher->id;")
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

    public function course_detail(View $view,$id)
    {
        $course = \ORM::for_table('courses')->where('id',2);
        return $view->make('courses.course-detail',[
            'course'=>$course
        ]);

    }
}