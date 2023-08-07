<?php

namespace App\Exceptions;

use App\Services\Api\ApiExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

    protected function prepareJsonResponse($request, Throwable $e)
    {
        return new JsonResponse([
            'error' => $e->getMessage(),
        ], $e->getStatusCode());
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiExceptionHandler::handel($e, $request);
            }
        });
    }
}
