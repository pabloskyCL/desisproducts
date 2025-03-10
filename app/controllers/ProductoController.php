<?php

namespace app\controllers;

use app\consts\EMessages;
use app\Http\Response;
use app\models\Bodega;
use app\models\Moneda;
use app\models\Producto;
use src\router\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // si da tiempo implementar interfaz o clase abastracta para los modelos

        $data = [];
        $bodegaModel = new Bodega();
        $monedaModel = new Moneda();

        $data['bodegas'] = $bodegaModel->getAll();
        $data['monedas'] = $monedaModel->getAll();

        $response = new Response(EMessages::CORRECT, data: $data);

        return $this->view('index', [
            'data' => $response->data,
            'code' => $response->code,
            'message' => $response->message,
        ]);
    }

    public function create(Request $request)
    {
        // TODO implementar dto
        $chks = ['plastico' => 'true' == $request->checkboxs->plastico ? true : false,
                    'madera' => 'true' == $request->checkboxs->madera ? true : false,
                    'metal' => 'true' == $request->checkboxs->metal ? true : false,
                    'vidrio' => 'true' == $request->checkboxs->vidrio ? true : false,
                    'textil' => 'true' == $request->checkboxs->textil ? true : false,
        ];

        var_dump($request->bodega);

        $checked = array_filter($chks, function ($k) {
            return true == $k;
        });

        if (!$request->codigo) {
            echo throw new \Exception('El codigo es obligatio', 1);
        }

        if (!ctype_alnum($request->nombre)) {
            echo throw new \Exception('El alias debe tener mas de 5 caracteres y debe contener solo letras y numeros', 1);
        }

        if (strlen($request->nombre) < 5) {
            echo throw new \Exception('El alias debe tener mas de 5 caracteres y debe contener solo letras y numeros', 1);
        }

        // if (!self::validaRut($request->rut)) {
        //     echo throw new \Exception('el rut tiene que estar en este formato 19192332-4', 1);
        // }
        if ('none' == $request->bodega) {
            echo throw new \Exception('Debe seleccionar una región', 1);
        }
        if ('none' == $request->sucursal) {
            echo throw new \Exception('Debe seleccionar una comuna', 1);
        }
        if ('none' == $request->moneda) {
            echo throw new \Exception('debe seleccionar un candidato', 1);
        }

        if (count($checked) < 2) {
            echo throw new \Exception('debe seleccionar almenos 2 influencias', 1);
        }

        $data = [
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'bodega' => $request->bodega,
            'sucursal' => intval($request->sucursal),
            'materiales' => join(',', array_keys($checked)),
            'moneda' => intval($request->moneda),
            'precio' => intval($request->precio),
            'descripcion' => intval($request->descripcion),
        ];

        $producto = new Producto();
        var_dump($data);
        $result = $producto->nuevoProducto($data);
        header('Content-Type: application/json');
        $response = new Response(EMessages::CORRECT);
        $response->setData($result);
        echo $response->json();
    }

    public static function validaRut($rutCompleto)
    {
        if (!preg_match('/^[0-9]+-[0-9kK]{1}/', $rutCompleto)) {
            return false;
        }
        $rut = explode('-', $rutCompleto);

        return strtolower($rut[1]) == self::dv($rut[0]);
    }

        public static function dv($T)
        {
            $M = 0;
            $S = 1;
            for (; $T; $T = floor($T / 10)) {
                $S = ($S + $T % 10 * (9 - $M++ % 6)) % 11;
            }

            return $S ? $S - 1 : 'k';
        }
}
