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


Route::get('/shopify', "AppAuthController@index");
Route::get('/confirmInstall', "AppAuthController@confirmInstall");

Route::group(['namespace'=>'Admin'],function(){

	Route::get("/admin", "IndexController@index");

});

Route::group(['namespace'=>'Order'],function(){

	Route::get("/order", "OrderController@index");
	Route::get("/order/getOrder", "OrderController@getOrder");

});

