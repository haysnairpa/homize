<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // You can add custom reporting logic here, like logging to external services
        });

        // Handle 404 Not Found errors
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->view('errors.404', [], 404);
        });

        // Handle 403 Forbidden errors
        $this->renderable(function (AuthorizationException $e) {
            return response()->view('errors.403', [], 403);
        });

        // Handle 401 Unauthorized errors
        $this->renderable(function (AuthenticationException $e) {
            return response()->view('errors.401', [], 401);
        });

        // Handle 419 Page Expired errors (CSRF token mismatch)
        $this->renderable(function (TokenMismatchException $e) {
            return response()->view('errors.419', [], 419);
        });

        // Handle 429 Too Many Requests errors
        $this->renderable(function (ThrottleRequestsException $e) {
            return response()->view('errors.429', [], 429);
        });

        // Handle 500 and other server errors
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return $this->prepareJsonResponse($request, $e);
            }

            $statusCode = 500;
            
            // Get status code if available
            if ($e instanceof HttpException) {
                $statusCode = $e->getStatusCode();
            }
            
            if ($statusCode === 500 || $statusCode === 503) {
                return response()->view('errors.500', ['exception' => $e], $statusCode);
            }
            
            // For any other HTTP exceptions that don't have specific handlers
            if ($e instanceof HttpException) {
                $view = 'errors.' . $statusCode;
                
                if (view()->exists($view)) {
                    return response()->view($view, ['exception' => $e], $statusCode);
                }
                
                // Fallback to 500 error page if specific error page doesn't exist
                return response()->view('errors.500', ['exception' => $e], $statusCode);
            }
            
            // Handle general exceptions with 500 error page
            return response()->view('errors.500', ['exception' => $e], 500);
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->view('errors.401', [], 401);
    }
}
