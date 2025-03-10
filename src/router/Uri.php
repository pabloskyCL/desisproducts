<?php

namespace src\router;

use app\Http\Response;

class Uri
{
    public $uri;
    public $method;
    public $function;
    public $matches;
    protected $request;
    protected $response;

    public function __construct($uri, $method, $function)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->function = $function;
    }

    public function match($uri)
    {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->uri);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $uri, $matches)) {
            return false;
        }

        if ($this->method != $_SERVER['REQUEST_METHOD'] && 'ANY' != $this->method) {
            return false;
        }

        array_shift($matches);

        $this->matches = $matches;

        return true;
    }

    private function execFunction()
    {
        $this->parseRequest();
        $this->response = call_user_func_array($this->function, $this->matches);
    }

    private function parseRequest()
    {
        $this->request = new Request($this->request);
        $this->matches[] = $this->request;
    }

    public function call()
    {
        try {
            $this->request = $_REQUEST;
            if (is_array($this->function)) {
                $this->controllerAction();
            } else {
                $this->execFunction();
                $this->printResponse();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function controllerAction()
    {
        $controllerName = $this->function[0];

        $action = $this->function[1];

        if (!$this->importController($controllerName)) {
            return;
        }

        $this->parseRequest();
        $classInstance = new $controllerName();
        $classInstance->setRequest($this->request);

        $launch = [$classInstance, $action];

        if (is_callable($launch)) {
            $this->response = call_user_func_array($launch, $this->matches);
        } else {
            throw new \Exception("El metodo $controllerName $action no existe.", -1);
        }
    }

    private function printResponse()
    {
        if (is_string($this->response)) {
            echo $this->response;
        } elseif (is_object($this->response) || is_array($this->response)) {
            $res = new Response();
            echo $res->json($this->response);
        }
    }

    public function importController($className)
    {
        $file = str_replace('\\', '/', $className).'.php';
        if (!file_exists($file)) {
            throw new \Exception("El controlador ($file) no existe");
        }

        require_once $file;

        return true;
    }
}
