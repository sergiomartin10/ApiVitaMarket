<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

// Definir las rutas de la API
Route::get('productos', [ProductoController::class, 'index']);  // Obtener todos los productos
Route::get('productos/{id}', [ProductoController::class, 'show']);  // Obtener un producto por ID
Route::post('productos', [ProductoController::class, 'store']);  // Crear un producto
Route::put('productos/{id}', [ProductoController::class, 'update']);  // Actualizar un producto
Route::delete('productos/{id}', [ProductoController::class, 'destroy']);  // Eliminar un producto
