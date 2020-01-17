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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/addProduct', 'HomeController@add')->name('products');
Route::post('/add', 'HomeController@adds')->name('add');
Route::post('/home/search', 'HomeController@search')->name('search');
Route::get('/report','HomeController@report')->name('report');
Route::post('/export','HomeController@export')->name('export');
Route::post('/home/mark','HomeController@mark')->name('mark');
