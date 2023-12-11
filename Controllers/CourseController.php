<?php

namespace Controllers;

use Classes\Auth;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;

class CourseController
{
    public function list(View $view)
    {
        $courses = \ORM::for_table('courses')
            ->raw_query("SELECT courses.name, courses.img, courses.description,courses.price,categories.category,courses.rating FROM courses INNER JOIN categories ON categories.id = courses.category_id ")
            ->findMany();
        return $view->make('courses.courses',[
            "courses" => $courses
        ]);

    }

    public function store(ServerRequest $request):RedirectResponse
    {
        $random = bin2hex(random_bytes(10));
        $img = explode('.', $request->getUploadedFiles()['img']->getClientFilename());
        $img_name = $random.time().'.'.$img[1];
        $request->getUploadedFiles()['img']->moveTo('img/'.$img_name);

        $teacher = \ORM::for_table('teachers')->where('user_id',$_SESSION['user-id'])->findOne();
        if($teacher){
            $teacher_id = $teacher->id;
        }
        else{
            return new RedirectResponse('/login');
        }


        $params = $request->getParsedBody();
        $course = \ORM::for_table('courses')->create();
        $course->name = $params['name'];
        $course->img = $img_name;
        $course->teacher_id = $teacher_id;
        $course->description = $params['description'];
        $course->price = $params['price'];
        $course->date_start = $params['date_start'];
        $course->category_id = $params['category'];
        $course->rating = 0;
        $course->save();

        return new RedirectResponse('/');

    }

}