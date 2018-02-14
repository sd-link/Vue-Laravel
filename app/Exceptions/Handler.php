<?php

namespace App\Exceptions;

use Theme;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        // if(app()->environment() != 'production') {
        //     return parent::render($request, $e);
        // }
        //
        // if ($e instanceof TokenMismatchException) {
        //     if ($request->ajax()){
        //         return response('Token Mismatch Exception', 401);
        //     }
        //
        //     return redirect()->route('login');
        // }
        //
        // if ($e instanceof ModelNotFoundException) {
        //     if($request->ajax()) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'This object does not exist anymore.'
        //         ], 404);
        //     }
        //     return response()->view(Theme::get().'.errors.404', ['e' => $e] , 404);
        // }
        //
        // if ($this->isHttpException($e))
        // {
        //     if( ! \Auth::check() ){
        //         return redirect()->route('login');
        //     }
        //
        //     $exception = FlattenException::create($e);
        //     $statusCode = $exception->getStatusCode($exception);
        //
        //     if (in_array($statusCode, array(403, 404, 500, 503))){
        //         if (View::exists(Theme::get().'/errors/'.$statusCode)){
        //          return response()->view(Theme::get().'/errors/'.$statusCode, ['e' => $e] , $statusCode);
        //         }
        //     }
        // }

        return parent::render($request, $e);
    }




    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
