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


//fetch posts api.
Route::get('/posts/archives','PostController@archives');
Route::get('/posts/tags','PostController@tags');
Route::get('/posts','PostController@index');






//check user's existence.
Route::get('/checkUser',function(Request $request){
  $userName = $request->input('userName');
	if (!User::exists($userName)) {
		abort(404);
    }
  $blog1 = ['name' => 'LoL',
       'slogan' => 'Hello, this is laravel\'s blog',
       'color' => 'is-danger'];
  $blog2 = ['name' => 'Dota2',
       'slogan' => 'Hello, this is dota2\'s blog',
       'color' => 'is-success'];
  if ($userName == 'laravel')
    return response()
              ->json(['status' => 'sucess',
                     'blog' => $blog1]);
  else
    return response()
              ->json(['status' => 'sucess',
                     'blog' => $blog2]);

});






//login & logout api
Route::prefix('auth')->group(function($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
});








//post management api.
Route::middleware(['refresh.token'])->group(function($router) {
   	 // $router->get('profile',function(){return 'done';});
    $router->post('post/{post}/edit','PostController@update');
    $router->post('post/{post}/delete','PostController@delete');
    $router->post('post/create','PostController@store');
});