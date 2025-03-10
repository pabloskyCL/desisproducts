<?php

namespace app\Http;

use app\consts\EMessages;

class Response
{
    public $code;
    public $message;
    public $data;

    public function __construct($code = null, $message = null, $data = null)
    {
        if (isset($code) && empty($message)) {
            $response = EMessages::getMessage($code);
            $this->code = $response->code;
            $this->message = $response->message;
            $this->data = $data;

            return;
        }

        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    public function json($obj = null)
    {
        header('Content-Type: application/json');
        http_response_code($this->code ?? 0);
        if (isset($obj)) {
            return json_encode($obj);
        }

        return json_encode($this);
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
