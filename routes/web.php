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


Route::get('/', "AppAuthController@index");
Route::get('/confirmInstall', "AppAuthController@confirmInstall");
Route::any('/register_shop', "AppAuthController@registerShop");

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){

	Route::get("/", "IndexController@index");
	Route::get("/shop/{id}", "IndexController@shop");

});

Route::group(['namespace'=>'Order'],function(){

	Route::get("/order", "OrderController@index");
	Route::get("/order/getOrder", "OrderController@getOrder");

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
