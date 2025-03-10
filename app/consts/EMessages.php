<?php

namespace app\consts;

use app\Http\Response;

class EMessages
{
    public const CORRECT = 'CORRECTO';
    public const ERROR = 'ERROR';
    public const INSERCION_EXITOSA = 'insercion_exitosa';

    public static function getMessage($codigo)
    {
        switch ($codigo) {
            case EMessages::CORRECT:
                return new Response(200, 'operación exitosa');

            case EMessages::INSERCION_EXITOSA:
                return new Response(204, 'registro realizado con exito');
            case EMessages::ERROR:
                return new Response(500, 'ha ocurrido un error');
        }
    }
}
