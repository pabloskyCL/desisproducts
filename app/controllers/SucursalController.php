<?php

namespace app\controllers;

use app\consts\EMessages;
use app\Http\Response;
use app\models\Sucursal;
use src\router\Request;

class SucursalController extends Controller
{
    public function sucursalesPorBodega(Request $request)
    {
        // si da tiempo implementar interfaz o clase abastracta para los modelos
        $data = [];
        $sucursal = new Sucursal();

        $data['sucursales'] = $sucursal->getSucursalesPorBodega($request->bodega);

        $response = new Response(EMessages::CORRECT);
        $response->setData($data);
        header('Content-Type: application/json');
        echo $response->json();
    }
}
