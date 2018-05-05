<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;
use App\User;
use GuzzleHttp\Client;
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





// function githubAuth(req, res) {
//   Axios.post('https://github.com/login/oauth/access_token', {
//     client_id: config.auth.github.clientId,
//     client_secret: config.auth.github.clientSecret,
//     code: req.body.code,
//     redirect_uri: req.body.redirectUri,
//     state: req.body.state,
//     grant_type: 'authorization_code'
//   }, { 'Content-Type': 'application/json' }).then(function (response) {
//     var responseJson = parseQueryString(response.data)
//     if (responseJson.error) {
//       res.status(500).json({ error: responseJson.error })
//     } else {
//       res.json(responseJson)
//     }
//   }).catch(function (err) {
//     res.status(500).json(err)
//   })
// }


//login & logout api
Route::prefix('auth')->group(function($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('register','AuthController@register');
    $router->post('activateUser','AuthController@activateUser');
    $router->post('forgotPassword','AuthController@forgotPassword');
    $router->post('resetPassword','AuthController@resetPassword');


    $router->post('github',function(Request $request){
        $client = new Client();
        $response = $client->post('https://github.com/login/oauth/access_token',
                    ['query' => ['client_id' => '47cbca2404878e6637ab',
                                'client_secret' => '90ad2bdb2db2b14ceeac4be7035e2cbd8f949b1a',
                                'code' => $request->input('code'),
                                'grant_type' => 'authorization_code',
                                'redirect_uri' => $request->input('redirectUri'),
                                'state' => $request->input('state')],
                     'headers' => ['Content-Type' => 'application/json']
                        ]);

        parse_str(html_entity_decode($response->getBody()), $res);
        $response = $client->get('https://api.github.com/user',['query' => $res]);
        
        $body = json_decode($response->getBody()->getContents());
        return ['user' => $body, 'access_token' => $res['access_token']];
        

    });
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

    $router->post('image/store','PostController@storeImages');






    //blog
    $router->post('/blog/update','BlogController@update');
});