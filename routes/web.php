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
Route::any('/shop/redact', "AppAuthController@redact");

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){

	Route::get("/", "IndexController@index");
	Route::get("/shop/{id}", "IndexController@shop");

});

Route::group(['namespace'=>'Order'],function(){

	Route::get("/order", "OrderController@index");
	Route::get("order/paid", "OrderController@paid");
	Route::get("/order/getOrder", "OrderController@getOrder");
	Route::get("/order/getOrderAndTemp", "OrderController@getOrderAndTemp");
	Route::get("/order/isSend", "OrderController@isSend");
	Route::get("/order/isCancel", "OrderController@isCancel");

});

Route::group(['prefix'=>'config','namespace'=>'Config'],function (){
    Route::get("/goods", "GoodsConfigController@autoPush");
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
