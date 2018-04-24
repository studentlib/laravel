<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Session\TokenMismatchException;

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
    public function render($request, Exception $e)
    {
        //TODO 这里一条自定义http错误自动跳转到首页
        if (getenv('APP_ENV') == 'production' && $e instanceof HttpException) {
            Log::error($e);
            return Redirect::to('/login');
        }
        if (getenv('APP_ENV') == 'production' && $e instanceof TokenMismatchException) {
            Log::error($e);
            if ($request->ajax()) {

                return Response::json(
                    [
                        'status' => 'failed',
                        'error' =>
                            [
                                'status_code' => 401,
                                'message' => '操作未完成，系统加载失败，重新登录或者刷新当前页面！'
                            ]
                    ]
                );
            }
            return Redirect::to('/login');

        }
        return parent::render($request, $e);
    }
}
