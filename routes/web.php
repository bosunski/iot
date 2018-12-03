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

Route::post('/sensor/update', 'SensorController@updateSensorValue');
Route::get('/energy', 'SensorController@showEnergyPage');
Route::get('/sensor', 'SensorController@showSpeedometer');
Route::get('/sensor/value', 'SensorController@getLatestValue');
Route::get('/', 'PagesController@index');
Route::get('/socket', 'SOcketController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/device/create', 'HomeController@createDevice')->name('create.device');
