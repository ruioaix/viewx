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
Route::get('/onlyhas', "MainController@onlyhas");
Route::get('/onlyin', "MainController@onlyin");
Route::get('/all', "MainController@all");
Route::get('/all/{node}', "MainController@node");
Route::get('/start', "MainController@start");
Route::get('/artisan', "MainController@artisan");


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

Route::get('/task/monitor', "TaskController@monitor");
Route::get('/task/monitor/{step}', "TaskController@mstep");
Route::get('/task/adjust', "TaskController@adjust");
Route::get('/task/adjust/{step}', "TaskController@adstep");
Route::get('/task/cheating', "TaskController@cheating");
Route::get('/task/cheating/{step}', "TaskController@ccstep");
Route::get('/task/manage', "TaskController@manage");
Route::post('/task/manage', "TaskController@manageupdate");

Route::get('/tools/fixparecord', "ToolsController@fixparecord");
Route::get('/tools/fixparecord/{step}', "ToolsController@fstep");
Route::get('/tools/fixparecordS/{step}', "ToolsController@fstepS");
Route::get('/tools/adjust', "AdjustController@monitor");
Route::get('/tools/adjust/{step}', "AdjustController@mstep");
