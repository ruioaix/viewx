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
Route::get('/account/circle', "AccountController@circle");
Route::get('/account/circle/{step}', "AccountController@cstep");
Route::get('/account/list', "AccountController@alist");
Route::get('/account/infoe/{step}', "AccountController@infoe");
Route::get('/account/infoi/{step}', "AccountController@infoi");
Route::get('/account/infoa/{step}', "AccountController@infoa");
Route::get('/account/health', "AccountController@health");
Route::get('/account/health/{step}', "AccountController@hstep");


Route::get('/adjust/monitor', "AdjustController@monitor");
Route::get('/adjust/monitor/{step}', "AdjustController@mstep");

Route::get('/tools/fixparecord', "ToolsController@fixparecord");
Route::get('/tools/fixparecord/{step}', "ToolsController@fstep");
Route::get('/tools/fixparecordS/{step}', "ToolsController@fstepS");
