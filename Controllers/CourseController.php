<?php

namespace Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;

class CourseController
{
    public function list(View $view)
    {
        $courses = \ORM::for_table('courses')->findMany();
        return $view->make('courses',[
            "courses" => $courses
        ]);

    }

    public function store(ServerRequest $request):RedirectResponse
    {
        $params = $request->getParsedBody();
        $course = \ORM::for_table('courses')->create();
        $course->
    }
}