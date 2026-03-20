<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (Throwable $e) {
            // Default values
            $code = null;
            $title = null;
            $message = null;
            $data = ['exception' => $e];

            if ($e instanceof ValidationException) {
                return null;
            }

            // Settings-driven maintenance mode override
            if ($e instanceof BadRequestHttpException) {
                $code = 400;
                $title = 'Bad Request';
                $message = 'The request could not be understood due to malformed syntax or invalid parameters.';
            }
            // Handle 401 Unauthorized
            elseif ($e instanceof AuthenticationException) {
                $code = 401;
                $title = 'Unauthorized Access';
                $message = 'You need to be authenticated to access this resource. Please log in to continue.';
            }
            // Handle 403 from policies / gates
            elseif ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
                if (request()->expectsJson()) {
                    $msg = 'You do not have permission to perform this action.';
                    if ($e instanceof AuthorizationException) {
                        $m = $e->getMessage();
                        if ($m !== '' && $m !== 'This action is unauthorized.') {
                            $msg = $m;
                        }
                    }

                    return response()->json([
                        'status' => 'error',
                        'message' => $msg,
                    ], 403);
                }

                $code = 403;
                $title = 'Access Forbidden';
                $message = 'You do not have permission to perform this action.';
            }
            // Handle 404 Not Found
            elseif ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                $code = 404;
                $title = 'Page Not Found';
                $message = 'The page you\'re looking for seems to have vanished into the digital void.';
            }
            // Handle 419 Session Expired
            elseif ($e instanceof HttpException && $e->getStatusCode() === 419) {
                $code = 419;
                $title = 'Session Expired';
                $message = 'Your session has expired for security reasons. Please refresh the page and try again.';
            }
            // Handle 429 Too Many Requests
            elseif ($e instanceof ThrottleRequestsException) {
                $code = 429;
                $title = 'Too Many Requests';
                $message = 'You have exceeded the request limit. Please try again in a moment.';
            }

            if ($code === null) {
                // Fallback: 500 Internal Server Error
                $code = 500;
                $title = 'Internal Server Error';
                $message = 'An unexpected error occurred. Our technical team has been notified.';
            }

            // Render the error view with consistent data
            return response()->view('errors.error', array_merge($data, [
                'code' => $code,
                'title' => $title,
                'message' => $message,
            ]), $code);
        });
    })->create();
