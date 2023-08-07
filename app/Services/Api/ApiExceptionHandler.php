<?php

namespace App\Services\Api;

use App\Exceptions\Visit\FactorZeroValueException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    public static function handel(Throwable $exception, Request $request): JsonResponse
    {
        return self::apiException($exception, $request);
    }

    public static function apiException(Throwable $exception, Request $request): JsonResponse
    {
        $response = match (get_class($exception)) {
            ValidationException::class => self::handelValidationException($exception),
            ModelNotFoundException::class => self::handelModelNotFoundException($exception),
            NotFoundHttpException::class => self::handelNotFoundHttpException($exception),
            FactorZeroValueException::class => self::handelFactorZeroValueException($exception),
            default => self::handelServerErrorException($exception),
        };

        return $response;
    }

    public static function handelValidationException(ValidationException $exception)
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $exception->errors(),
        ], 422);
    }

    public static function handelNotFoundHttpException(NotFoundHttpException $exception)
    {
        return response()->json([
            'message' => 'Record not found.',
        ], 404);
    }

    public static function handelModelNotFoundException(ModelNotFoundException $exception)
    {
        return response()->json([
            'message' => 'Model not found.',
        ], 404);
    }

    public static function handelServerErrorException($exception)
    {
        $details = [];

        if (config('app.showExceptionsTracing')) {
            $details = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ];
        }

        return response()->json(array_merge([
            'message' => $exception->getMessage() ?? 'Server error.',
        ], $details), 500);
    }

    // handel FactorZeroValueException
    public static function handelFactorZeroValueException(FactorZeroValueException $exception)
    {
        return response()->json([
            'message' => $exception->getMessage(),
        ], 422);
    }
}
