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
Route::get('/proxy/circle', "ProxyController@source");
Route::get('/proxy/circle/{step}', "ProxyController@sstep");
Route::get('/proxy/health', "ProxyController@wtime");
Route::get('/proxy/health/{step}', "ProxyController@wstep");
Route::get('/proxy/errortype', "ProxyController@errortype");


Route::get('/account', "AccountController@index");
Route::get('/account/goodorbad', "AccountController@goodorbad");
Route::get('/account/list', "AccountController@alist");
Route::get('/account/detail/{step}', "AccountController@step");
Route::get('/account/{email}', "AccountController@info");


Route::get('/adjust', "AdjustController@index");
