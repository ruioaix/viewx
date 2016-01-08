<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', "MainController@index");

Route::get('/all', "MainController@all");
Route::get('/all/{node}', "MainController@node");

Route::get('/circle', "CircleController@circle");
Route::get('/circle/{ccstep}', "CircleController@ccstep");

Route::get('/task/monitor', "TaskController@monitor");
Route::get('/task/monitor/{step}', "TaskController@mstep");
