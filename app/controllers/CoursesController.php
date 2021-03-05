<?php

class CoursesController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Course');
    }

    public function index()
    {
        $session = new Session();

            $courses = $this->model->getCourses();
            $data = [
                'titulo'    => 'Cursos en línea',
                'subtitle'  => 'Cursos',
                'menu'      => true,
                'active'    => 'courses',
                'data'      => $courses,
            ];
            $this->view('courses/index', $data);

    }
}
