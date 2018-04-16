<?php

use App\Http\Controllers\PostController;
use App\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/posts/archives','PostController@archives');
Route::get('/posts/tags','PostController@tags');
Route::get('/posts/{postId?}','PostController@index');

Route::get('/checkUser',function(Request $request){
	if (!User::where('name', '=', $request->input('userName'))->exists()) {
		abort(404);
    }
    return 'done';
   
});


Route::prefix('auth')->group(function($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');


});

Route::middleware(['refresh.token'])->group(function($router) {
   	 // $router->get('profile',function(){return 'done';});
    $router->post('post/{post}/edit','PostController@update');
    $router->post('post/{post}/delete','PostController@delete');
    $router->post('post/create','PostController@store');
});