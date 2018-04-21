<?php

namespace App\Http\Controllers;

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
        return $params;
        $user = User::create($params);

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

       // 使用 Auth 登录用户，如果登录成功，则返回 201 的 code 和 token，如果登录失败则返回
        return ($token = Auth::guard('api')->attempt($params))
            ? response(['token'     => 'Bearer ' . $token,
                        'userName'  => User::where('email',$params['email'])->first()->name], 201)
            : response(['errors' => [  'email' => ['Email or password is invalid'],
                                       'password' => ['Email or password is invalid'] 
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