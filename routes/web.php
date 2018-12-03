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
Route::get('/overview/{deviceId}', 'SensorController@showEnergyPage')->name('show.overview');
Route::get('/sensor', 'SensorController@showSpeedometer');
Route::get('/sensor/{deviceId}/value', 'SensorController@getLatestValue');
Route::get('/', 'PagesController@index');
Route::get('/socket', 'SOcketController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/device/create', 'DeviceController@showNewDeviceForm')->name('create.device');
Route::post('/device/create', 'DeviceController@createDevice')->name('create.device');
Route::get('/devices', 'DeviceController@listDevices')->name('device.list');
