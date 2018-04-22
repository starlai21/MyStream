<?php

use App\Http\Controllers\BlogController;
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


//fetch posts api.
Route::get('/posts/archives','PostController@archives');
Route::get('/posts/tags','PostController@tags');
Route::get('/posts','PostController@index');






//check user's existence.
Route::get('/blog','BlogController@index');








//login & logout api
Route::prefix('auth')->group(function($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('register','AuthController@register');
    $router->post('activateUser','AuthController@activateUser');

    $router->post('checkEmail','AuthController@checkEmail');
    $router->post('checkUserName','AuthController@checkUserName');

});








//post management api.
Route::middleware(['refresh.token'])->group(function($router) {
	//post
    $router->post('post/{post}/edit','PostController@update');
    $router->post('post/{post}/delete','PostController@delete');
    $router->post('post/create','PostController@store');

    //blog
    $router->post('/blog/update','BlogController@update');
});