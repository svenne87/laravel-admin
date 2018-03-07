<?php

namespace App\Exceptions;

use Exception;
use Lang;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        if ($request->expectsJson()) {

            // Validation errors
            if ($exception instanceof ValidationException) {
                return response()->json(['errors' => $exception->errors()], 422);
            }

            // Forbidden resource error
            if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException || $exception instanceof AuthorizationException) {
                return response()->json(['errors' => Lang::get('errors.403')], 403);
            }
            
            // UnAuthenticated error
            if ($exception instanceof AuthenticationException) {
                return response()->json(['errors' => Lang::get('errors.401')], 401);
            }

            // Default
            $response = array('errors' => Lang::get('errors.common_error'));

            if ($this->shouldReport($exception) && !app()->environment('production')) {
                $response['exception'] = get_class($exception);
                $response['message'] = $exception->getMessage();
                $response['trace'] = $exception->getTrace();
            }
            
            // Default response of 400
            $status = 400;
    
            // If this exception is an instance of HttpException
            if ($exception instanceof HttpException) {
                // Grab the HTTP status code from the Exception
                $status = $exception->getStatusCode();
            }
            
            // Return a JSON response with the response array and status code
            return response()->json($response, $status);
        }
    
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('/errors/404', [], 404);
        } 

        if ($this->shouldReport($exception) && app()->environment('production')) {
            return response()->view('/errors/500', [], 500);
            $exception = new HttpException(500, $exception->getMessage(), $exception);
        }

        return parent::render($request, $exception);
    }
}
