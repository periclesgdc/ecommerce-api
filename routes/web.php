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
    //return view('welcome');
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'produtos'], function ($router) {
	Route::get('/', 'ProdutoController@listar')->name('produtos');
	Route::get('/alterar/{id}', 'ProdutoController@alterar')->name('alterar');
	Route::post('/alterar/{id}', 'ProdutoController@alterar');
	Route::get('/deletar/{id}', 'ProdutoController@deletar')->name('deletar');
});

Route::get('/clientes');
Route::get('/pedidos');