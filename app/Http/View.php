<?php

namespace app\Http;

class View
{
    protected $variables;
    protected $input;

    public function __construct()
    {
    }

    public function render($file, $variables = null)
    {
        $this->variables = $variables;

        $file = __DIR__.'/../../resources/views/'.$file;
        

        if (isset($this->variables) && is_array($this->variables)) {
            extract($this->variables);
        }

        if (file_exists($file)) {
            return include $file;
        } elseif (file_exists($file.'.php')) {
            return include $file.'.php';
        } else {
            echo '<h1>El archivo no existe</h1>';
        }
    }
}
