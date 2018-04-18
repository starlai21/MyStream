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


Route::view('/{url}', 'welcome')
		->where('url','.*');
// Route::view('/{url}/{query}', 'welcome')
// 	 ->where(['url' => 'blog|admin|login'])
//      ->where('query', '.*');
// Route::view('*','welcome');






// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
