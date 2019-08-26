<?php

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
    return view('welcome');
});

//Route::get('test1', function () {
//    return view('welcome');
//});

Route::get('test1', 'TestController@test1');

Route::match(['get', 'post'], 'test2', 'TestController@test2');


//SoapCallOutController 路由配置
Route::get('loginSF', 'SoapCallOutController@loginSF');
Route::get('testHandshake', 'SoapCallOutController@testHandshake');
Route::get('testConnect', 'SoapCallOutController@testConnect');









