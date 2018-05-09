<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;

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
Route::get('/posts','PostController@posts');
Route::get('/post','PostController@post');



Route::get('/comments','CommentController@index');



//check user's existence.
Route::get('/blog','BlogController@index');





//login & logout api
Route::prefix('auth')->group(function($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('register','AuthController@register');
    $router->post('activateUser','AuthController@activateUser');
    $router->post('forgotPassword','AuthController@forgotPassword');
    $router->post('resetPassword','AuthController@resetPassword');


    $router->post('github','AuthController@authGithub');
    $router->post('authRegister','AuthController@authRegister');


    $router->post('checkEmail','AuthController@checkEmail');
    $router->post('checkUserName','AuthController@checkUserName');

});








//post management api.
Route::middleware(['refresh.token'])->group(function($router) {
	//post
    $router->post('post/{post}/edit','PostController@update');
    $router->post('post/{post}/delete','PostController@delete');
    $router->post('post/create','PostController@store');
    $router->post('post/{post}/toggle','PostController@toggle');

    //image
    $router->post('image/store','PostController@storeImages');



    //comment
    $router->post('comment/store','CommentController@store');
    $router->post('reply/store','CommentController@storeReply');

    $router->post('comment/toggleLike','CommentController@toggleLike');

    $router->post('reply/toggleLike','CommentController@toggleLike');






    //blog
    $router->post('/blog/update','BlogController@update');
});