<?php

namespace app\controllers;

use app\Http\View;

class Controller
{
    protected $request;
    private $view;

    public function __construct()
    {
    }

    public function view($file, $variables = null)
    {
        if (empty($this->view)) {
            $this->view = new View();
        }

        return $this->view->render($file, $variables);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }
}
