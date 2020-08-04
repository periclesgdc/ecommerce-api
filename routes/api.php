<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/', function () {
    return response()->json(['message' => env('APP_NAME'), 'status' => 'Connected']);;
});

Route::resource('clientes', 'ClienteController');
Route::resource('produtos', 'ProdutoController');
Route::resource('pedidos', 'PedidoController');

Route::group(['prefix' => 'auth'], function ($router) {
	Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
	Route::post('me', 'AuthController@me');
});