<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use ErrorException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {   
        // if ($exception instanceof NotFoundHttpException){
        //     return response()->json([
        //         'result' => null,
        //         'statusCode' => 404,
        //         'message' => 'Not Found.'
        //     ],Response::HTTP_NOT_FOUND);
        // }

        // if ($exception instanceof \Illuminate\Database\QueryException) 
        // {
        //     return response()->json([
        //         'result' => null,
        //         'statusCode' => 500,
        //         'message' => 'Internal Server Error.',
        //     ], 500);          
        // }

        // if ($exception instanceof ErrorException) 
        // {
        //     return response()->json([
        //         'result' => null,
        //         'statusCode' => 500,
        //         'message' => 'Internal Server Error.',
        //     ], 500);          
        // }
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'result' => null,
            'statusCode' => 401,
            'message'=> "Unauthorized."
        ], 401);
    }
}
