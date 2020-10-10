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

Route::group(['namespace' => 'API'], function () {
    Route::post('login', 'UserController@login');
    Route::post('signup', 'UserController@signup');
  
    Route::group(['middleware' => 'auth:api'], function() {
      Route::get('logout', 'UserController@logout');
      Route::get('/', 'TaskController@index');
      Route::get('/todo', 'TaskController@index');
      Route::get('/todo/add', 'TaskController@create');
      Route::post('/todo/add', 'TaskController@store');
      Route::get('/todo/{id}', 'TaskController@edit');
      Route::delete('/todo/{id}', 'TaskController@destroy');
      Route::patch('/todo/{id}', 'TaskController@update');
      Route::get('/todo/filter-ip/{ip}', 'TaskController@filterIpRanges');
    });
});
