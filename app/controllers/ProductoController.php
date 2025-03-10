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

        $checked = array_filter($chks, function ($k) {
            return true == $k;
        });

        if (!preg_match('/^[a-zA-Z0-9]{5,15}$/', $request->codigo)) {
            $response = new Response(500, 'El codigo deben ser letras y numeros con un minimo de 5 caracteres y un maximo de 15');
            echo $response->json();

            return;
        }

        if (!preg_match('/^[a-zA-Z0-9 ]{2,50}$/', $request->nombre)) {
            $response = new Response(500, 'El nombre debe tener mas de 5 caracteres y debe contener solo letras y numeros');
            echo $response->json();

            return;
        }

        if ('none' == $request->bodega) {
            $response = new Response(500, 'Debe seleccionar una bodega');
            echo $response->json();

            return;
        }
        if ('none' == $request->sucursal) {
            $response = new Response(500, 'Debe seleccionar una sucursal');
            echo $response->json();

            return;
        }
        if ('none' == $request->moneda) {
            $response = new Response(500, 'Debe seleccionar una moneda');
            echo $response->json();

            return;
        }

        if (count($checked) < 2) {
            $response = new Response(500, 'Debe seleccionar almenos 2 materiales');
            echo $response->json();

            return;
        }

        if (!preg_match('/^.{10,1000}$/s', $request->descripcion)) {
            $response = new Response(500, 'la descripcion debe tener una longitud minima de 10 caracteres y maxima de 1000');
            echo $response->json();

            return;
        }

        $data = [
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'bodega' => $request->bodega,
            'sucursal' => intval($request->sucursal),
            'materiales' => join(',', array_keys($checked)),
            'moneda' => intval($request->moneda),
            'precio' => intval($request->precio),
            'descripcion' => $request->descripcion,
        ];

        $producto = new Producto();

        $result = $producto->nuevoProducto($data);
        header('Content-Type: application/json');
        if (isset($result['error'])) {
            $response = new Response(500, EMessages::ERROR);
            $response->setData($result);
            echo $response->json();

            return;
        }
        $response = new Response(EMessages::CORRECT);
        $response->setData($result);
        echo $response->json();
    }
}
