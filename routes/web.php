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
    return redirect()->route('product.index');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'product', 'as' => 'product.','middleware' => ['auth']], function () {
    Route::get('/', 'FormController@index')->name('index');
    Route::get('/add', 'FormController@add')->name('add');
    Route::get('{id}/edit', 'FormController@edit')->name('edit');
    Route::post('/save', 'FormController@save')->name('save');
    Route::get('/list', 'FormController@list')->name('list');
    Route::post('/delete', 'FormController@delete')->name('delete');
});
