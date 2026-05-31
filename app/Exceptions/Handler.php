<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;
use Symfony\Component\ErrorHandler\Error\UndefinedFunctionError;
use Symfony\Component\ErrorHandler\Error\UndefinedMethodError;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
{
    if ($exception instanceof FatalError && $exception->getMessage() === 'Maximum execution time of 60 seconds exceeded' || $exception->getMessage() === 'Maximum execution time of 120 seconds exceeded') {
    return redirect()->back();
    }

   


   if ($exception instanceof ErrorException) {
        $errorFile = $exception->getFile();

        // Check if the error occurred due to a specific file not being found
        if (strpos($errorFile, 'vendor/composer') !== false && strpos($errorFile, 'Psr/Log/LoggerInterface.php') !== false) {
             return redirect()->back();
             // Replace 'error_route' with the desired route for error handling
        }
    }


 if ($exception instanceof HttpException && $exception->getStatusCode() == 419) {
        return Redirect::back()->with('error', 'Page expired. Please try again.'); // Replace with the appropriate redirect or error handling
    }
     if ($exception instanceof MethodNotAllowedHttpException) {
        return Redirect::back()->with('error', 'The requested method is not allowed. Please try again with the appropriate method.'); // Replace with the appropriate redirect or error handling
    }

    if ($exception instanceof HttpException && $exception->getStatusCode() == 500) {
        return Redirect::back()->with('error', 'An internal server error occurred. Please try again later.'); // Replace 'custom_error_route' with the appropriate route for your custom error page
    }
     if ($exception instanceof TokenMismatchException) {
        // Handle the token mismatch exception
        return Redirect::back()->with('error', 'error that page is not working ');
    }
     if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
        return redirect()->back()->with('error', 'The requested page was not found.');
    }
     return parent::render($request, $exception);
}
}
