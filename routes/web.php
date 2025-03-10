<?php

use app\controllers\ProductoController;
use app\controllers\SucursalController;
use src\router\Route;

Route::get('/', [ProductoController::class, 'index']);
Route::get('/sucursales', [SucursalController::class, 'sucursalesPorBodega']);
Route::post('/producto', [ProductoController::class, 'create']);
