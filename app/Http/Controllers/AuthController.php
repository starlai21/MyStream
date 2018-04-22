<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Notifications\UserPasswordReset;
use App\Notifications\UserRegisteredSuccessfully;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function register(){
        $params = request()->validate(['email' => 'required | email | unique:users',
                                        'name' => 'required | alpha_dash | unique:users',
                                        'password' => 'required | max: 20| min:6 | confirmed']);

        $params['activation_code'] = str_random(30).time();
        $params['password'] = Hash::make($params['password']);

        $user = User::create($params);
        $user->notify(new UserRegisteredSuccessfully($user));
        return ['status' => 'success'];
    }


    public function activateUser(){
        $user = User::where('activation_code', request()->input('activation_code'))->first();
        if (!$user) {
            return ['message' => 'The activation code is invalid.',
                    'status'  => 'error'];
        }
        $user->activated = true;
        $user->activation_code = null;
        Blog::create(['user_id' => $user->id, 'name' => $user->name]);
        $user->save();
        $token = auth()->login($user);
        return ['token' => 'Bearer ' . $token,
                'userName' => $user->name,
                'status' => 'success',
                'message' => 'Activated'];

    }

    public function forgotPassword(){
        $param = request()->validate(['email' => 'required | email']);
        $user = User::where('email',$param['email'])->first();
        if(!$user){
            return ['message' => 'The email is invalid.',
                    'status'  => 'error'];
        }
        else{
            $user->password_reset_code = str_random(30).time();
            $user->save();
            $user->notify(new UserPasswordReset($user));
            return ['message' => 'Please check your email box.',
                    'status'  => 'success'];
        }
    }

    public function resetPassword(){
        $user = User::where('password_reset_code', request()->input('password_reset_code'))
                        ->first();
        if (!$user) {
            return ['message' => 'The password reset code is invalid.',
                    'status'  => 'error'];
        }
        else{
            $param = request()->validate(['password' => 'required | max:20 | min:6|confirmed']);
            $param['password'] = Hash::make($param['password']);
            $user->update($param);
            $user->password_reset_code = null;
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

        $token = Auth::guard('api')->attempt($params);
        if ($token){
            $user = User::where('email',$params['email'])->first();
            if (!$user->activated){
                return response(['status' => 'error',
                                'message' => 'Your account has not been activated.']);
            }
            else{
                return response(['token'     => 'Bearer ' . $token,
                                'userName'  => $user->name,
                                'status'  => 'success'], 201);
            }
        }
        else{
            return response(['errors' => [  
                                'email'     => ['Email or password is invalid'],
                                'password'  => ['Email or password is invalid'] 
                            ]
                        ], 400);
        }
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
        if (User::where('email',$param['email'])->first())
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
}