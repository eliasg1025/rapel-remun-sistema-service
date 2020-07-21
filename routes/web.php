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

/**
 * Pages
 */
Route::get('/', 'Web\ViewController@index');
Route::get('/panel', 'Web\ViewController@panel');
Route::get('/login', 'Web\ViewController@login');
Route::get('/usuarios', 'Web\ViewController@usuarios');
Route::get('/trabajadores', 'Web\ViewController@trabajadores');
Route::get('/registro-individual', 'Web\ViewController@registroIndividual');
Route::get('/registro-individual/editar/{id}', 'Web\ViewController@editarRegistroIndividual');
Route::get('/registro-masivo', 'Web\ViewController@registorMasivo');
Route::get('/cuentas', 'Web\ViewController@cuentas');

Route::post('/login', 'Web\AuthController@login');
Route::post('/logout', 'Web\AuthController@logout');

Route::get('/ficha/{contrato}', 'ContratoController@verFichaIngreso');
Route::get('/ficha/cambio-cuenta/{cuenta}', 'CuentasController@verFichaCuenta');
Route::get('/test', 'ContratoController@test');


Route::group(['prefix' => 'descargar'], function() {
    Route::post('/observados', 'Web\TrabajadorController@descargarObservado');
    Route::post('/cuentas', 'Web\CuentasController@descargarCuentas');
});
