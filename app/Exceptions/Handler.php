<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // // 参数验证错误的异常，我们需要返回 400 的 http code 和一句错误信息
        // if ($exception instanceof ValidationException) {
        //     return response(['error' => array_first(array_collapse($exception->errors()))], 400);
        // }
        
        // // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
        // if ($exception instanceof UnauthorizedHttpException) {
        //     return response($exception->getMessage(), 401);
        // }
        // if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException){
        //         switch (get_class($exception->getPrevious())) {
        //             case \Tymon\JWTAuth\Exceptions\TokenExpiredException::class:
        //                 return response()->json([
        //                     'status' => 'error',
        //                     'message' => 'Token has expired'
        //                 ], 401);
        //             case \Tymon\JWTAuth\Exceptions\TokenInvalidException::class:
        //             case \Tymon\JWTAuth\Exceptions\TokenBlacklistedException::class:
        //                 return response()->json([
        //                     'status' => 'error',
        //                     'message' => 'Token is invalid'
        //                 ], 402);
        //             default:
        //                 break;
        //     }
        // }

        if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
            return response()->json([
                            'status' => 'error',
                            'message' => 'Token has expired'
                        ], 401);
        return parent::render($request, $exception);
    }
}