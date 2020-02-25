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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/addProduct', 'HomeController@add')->name('products');
Route::post('/add', 'HomeController@adds')->name('add');
Route::get('/home/search', 'HomeController@search')->name('search');
Route::get('/report','HomeController@report')->name('report');
Route::post('/export','HomeController@export')->name('export');
Route::post('/exportDefault','HomeController@exportDefault')->name('exportDefault');
Route::post('/home/mark','HomeController@mark')->name('mark');

//Route::match(['get','post'], '/home/search',[
//    'as' => 'search',
//    'uses' => 'HomeController@search'
//]);

Route::get('/newdesign','HomeController@newDesign')->name('newDesign');

Route::get('/packets','PacketController@index')->name('packets');
Route::get('/addpacket','PacketController@addpacket')->name('packet.store');
Route::post('/addpacket','PacketController@addpackets')->name('packet.store');

Route::get('/products','ProductController@index')->name('products');
Route::get('/addproduct','ProductController@addProduct')->name('product.store.get');
Route::post('/addproduct','ProductController@addProducts')->name('product.store.post');

Route::get('/allProducts','HomeController@allProducts')->name('allProducts');



