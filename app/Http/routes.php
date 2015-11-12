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
Route::get('/proxy/errortype', "ProxyController@errortype");
Route::get('/proxy', "ProxyController@index");
Route::get('/adjust', "AdjustController@index");
Route::get('/account', "AccountController@index");
