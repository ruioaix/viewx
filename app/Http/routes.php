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
Route::get('/about', "MainController@about");


Route::get('/proxy/monitor', "ProxyController@monitor");
Route::get('/proxy/monitor/{step}', "ProxyController@mstep");
Route::get('/proxy/circle', "ProxyController@circle");
Route::get('/proxy/circle/{step}', "ProxyController@cstep");
Route::get('/proxy/health', "ProxyController@health");
Route::get('/proxy/health/{step}', "ProxyController@hstep");
Route::get('/proxy/errortype', "ProxyController@errortype");


Route::get('/account/monitor', "AccountController@monitor");
Route::get('/account/monitor/{step}', "AccountController@mstep");
Route::get('/account/list', "AccountController@alist");
Route::get('/account/info/{step}', "AccountController@info");


Route::get('/adjust', "AdjustController@index");
