<?php

namespace App\Http\Controllers;

use App\Blog;
use App\EmailLoginUser;
use App\Notifications\UserPasswordReset;
use App\Notifications\UserRegisteredSuccessfully;
use App\SocialLoginUser;
use App\Transformers\UserTransformer;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function register(){
        $params = request()->validate(['email' => 'required | email | unique:email_login_users',
                                        'name' => 'required | alpha_dash | unique:users',
                                        'password' => 'required | max: 20| min:6 | confirmed']);

        $params['activation_code'] = str_random(30).time();
        $params['password'] = Hash::make($params['password']);

        $user = User::create(['name' => $params['name']]);

        $new_params = array_filter($params, function ($key) {
            return $key !== 'name';
        }, ARRAY_FILTER_USE_KEY);
        $new_params['user_id'] = $user->id;
        EmailLoginUser::create($new_params);


        $user->notify(new UserRegisteredSuccessfully($user));
        return ['status' => 'success'];
    }


    public function activateUser(){
        $emailLoginUser = EmailLoginUser::where('activation_code', request()->input('activation_code'))->first();
        if (!$emailLoginUser) {
            return ['message' => 'The activation code is invalid.',
                    'status'  => 'error'];
        }
        $emailLoginUser->activated = true;
        $emailLoginUser->activation_code = null;
        $user = $emailLoginUser->user;
        Blog::create(['user_id' => $user->id, 'name' => $user->name]);
        $emailLoginUser->save();
        $token = auth()->login($user);
        return ['token' => 'Bearer ' . $token,
                'userName' => $user->name,
                'status' => 'success',
                'message' => 'Activated'];

    }

    public function forgotPassword(){
        $param = request()->validate(['email' => 'required | email']);
        $emailLoginUser = EmailLoginUser::where('email',$param['email'])->first();
        if(!$emailLoginUser){
            return ['message' => 'The email is invalid.',
                    'status'  => 'error'];
        }
        else{
            $user = $emailLoginUser->user;
            $emailLoginUser->password_reset_code = str_random(30).time();
            $emailLoginUser->save();
            $user->notify(new UserPasswordReset($user));
            return ['message' => 'Please check your email box.',
                    'status'  => 'success'];
        }
    }

    public function resetPassword(){
        $emailLoginUser = EmailLoginUser::where('password_reset_code', request()->input('password_reset_code'))
                        ->first();
        if (!$emailLoginUser) {
            return ['message' => 'The password reset code is invalid.',
                    'status'  => 'error'];
        }
        else{
            $param = request()->validate(['password' => 'required | max:20 | min:6|confirmed']);
            $param['password'] = Hash::make($param['password']);
            $param['password_reset_code'] = null;
            $emailLoginUser->update($param);
            return ['message' => 'Your password has been reset.',
                    'status'  => 'success'];

        }
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $rules = [
            'email'   => [
                'required',
                'email',
            ],
            'password' => 'required|string|min:6|max:20',
         ];
          
        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $params = $this->validate($request, $rules);

        $emailLoginUser = EmailLoginUser::where('email',$params['email'])->first();
        if ($emailLoginUser) {
            if (!$emailLoginUser->activated)
                return response(['status' => 'error',
                                'message' => 'Your account has not been activated.']);
            
            if (Hash::check($params['password'],$emailLoginUser->password)){
                $user = $emailLoginUser->user;
                $token = auth()->login($user);
                return response(['token'     => 'Bearer ' . $token,
                                'userName'  => $user->name,
                                'status'  => 'success'], 201);
            }
        }
             
        return response(['errors' => [  
                            'email'     => ['Email or password is invalid'],
                            'password'  => ['Email or password is invalid'] 
                        ]
                    ], 400);        
    }

    /**
     * 处理用户登出逻辑
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response(['message' => 'logout']);
    }



    public function checkEmail(){
        $param = request()->validate(['email' => 'email | required']);
        if (EmailLoginUser::where('email',$param['email'])->first())
            return ['valid' => false];
        else
            return ['valid' => true];
    }


    public function checkUserName(){
        $param = request()->validate(['userName' => 'required | alpha_dash']);
        if (User::where('name',$param['userName'])->first())
            return ['valid' => false];
        else
            return ['valid' => true];
    }



    public function authGithub(){
        $client = new Client();
        $response = $client->post('https://github.com/login/oauth/access_token',
                    ['query' => ['client_id' => config('services.github.client_id'),
                                'client_secret' => config('services.github.client_secret'),
                                'code' => request()->input('code'),
                                'grant_type' => 'authorization_code',
                                'redirect_uri' => request()->input('redirectUri'),
                                'state' => request()->input('state')],
                     'headers' => ['Content-Type' => 'application/json']
                        ]);


        parse_str(html_entity_decode($response->getBody()), $res);

        $response = $client->get('https://api.github.com/user',['query' => $res]);
        
        $body = json_decode($response->getBody()->getContents());

        $socialLoginUser = SocialLoginUser::where('provider_user_id',$body->id)
                                    ->where('provider','github')
                                    ->first();

        if ($socialLoginUser){
            $user = $socialLoginUser->user;
            $token = auth()->login($user);
            return ['token' => 'Bearer ' . $token,
                            'status' => 'success',
                            'userName' => $user->name];
        }
        else{
            return ['access_token' => $res['access_token']];
        }

    }

    public function authRegister(){
        $params = request()->validate(
                                ['name' => 'required | alpha_dash | unique:users',
                                'provider' => 'required',
                                'access_token' => 'required']);
        $client = new Client();
        if ($params['provider'] == 'github'){
            $response = $client->get('https://api.github.com/user',
                                 ['query' => 
                                        ['access_token' => $params['access_token'] ]
                                    ]);
            $body = json_decode($response->getBody()->getContents());
            $socialLoginUser = SocialLoginUser::where('provider_user_id',$body->id)
                                    ->where('provider','github')
                                    ->first();
            //registerd
            if ($socialLoginUser){
                //it is a login request. return token.. 
                return ['status' => 'error',
                        'message' => 'You has already registered before, please log in to proceed.'];
            }
            else{
                //it is a register request.
                $user = User::create(['name' => $params['name']]);
                $socialLoginUser = SocialLoginUser::create(['provider_user_id' => $body->id,
                                                            'user_id' => $user->id]); 
                Blog::create(['user_id' => $user->id, 'name' => $user->name]);
                $token = auth()->login($user);
                return ['token' => 'Bearer ' . $token,
                            'status' => 'success',
                            'message' => 'Congrats! Your blog has been successfully created.'];

            }

        }

    }
}