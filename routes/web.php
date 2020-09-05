<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.app');
});

Route::resource('productos','ProductoController');

//Ruta extra para agregar rol a un tercero.
Route::get('terceros/{tercero}/rol', 'TerceroController@updateRoles');
Route::resource('terceros','TerceroController');

Route::resource('compras','CompraController');

Route::resource('detalleCompra','DetalleCompraController');

