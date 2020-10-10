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

Route::group(['namespace' => 'WEB'], function () {
    Route::get('/', 'TaskController@index')->name('task.index');
    Route::get('/todo', 'TaskController@index')->name('task.index');
    Route::get('/todo/add', 'TaskController@create')->name('task.create');
    Route::post('/todo/add', 'TaskController@store')->name('task.store');
    Route::get('/todo/{id}', 'TaskController@edit')->name('task.edit');
    Route::delete('/todo/{id}', 'TaskController@destroy')->name('task.destroy');
    Route::patch('/todo/{id}', 'TaskController@update')->name('task.update');
    Route::get('/test', 'TaskController@createView');
    Route::get('/image/shape1', 'TaskController@createImage1')->name('shape1');
    Route::get('/image/shape2', 'TaskController@createImage2')->name('shape2');
    Route::get('/image/shape3', 'TaskController@createImage3')->name('shape3');
    Route::get('/geometric-shapes', function(){
        return view('shapes');
    });
    Route::get('/command', function(){
        return view('command');
    });
    Route::get('/restapi', function(){
        return view('api');
    });

});

