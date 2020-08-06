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
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth.basic', 'prefix' => 'produtos'], function ($router) {
	Route::get('/', 'ProdutoController@listar')->name('produtos');
	Route::get('/novo', 'ProdutoController@novo');
	Route::post('/novo', 'ProdutoController@novo');
	Route::get('/alterar/{id}', 'ProdutoController@alterar');
	Route::post('/alterar/{id}', 'ProdutoController@alterar');
	Route::get('/deletar/{id}', 'ProdutoController@deletar');
});

Route::group(['middleware' => 'auth.basic', 'prefix' => 'clientes'], function ($router) {
	Route::get('/', 'ClienteController@listar')->name('clientes');
	Route::get('/alterar/{id}', 'ClienteController@alterar');
	Route::post('/alterar/{id}', 'ClienteController@alterar');
	Route::get('/deletar/{id}', 'ClienteController@deletar');
});

Route::get('/pedidos', 'PedidoController@listar');