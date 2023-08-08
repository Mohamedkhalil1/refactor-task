<?php

namespace App\Services\Api;

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
        return match (get_class($exception)) {
            ValidationException::class => self::handelValidationException($exception),
            ModelNotFoundException::class => self::handelModelNotFoundException($exception),
            NotFoundHttpException::class => self::handelNotFoundHttpException($exception),
            default => self::handelServerErrorException($exception),
        };

    }

    public static function handelValidationException(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $exception->errors(),
        ], 422);
    }

    public static function handelNotFoundHttpException(NotFoundHttpException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'Record not found.',
        ], 404);
    }

    public static function handelModelNotFoundException(ModelNotFoundException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'Model not found.',
        ], 404);
    }

    public static function handelServerErrorException($exception): JsonResponse
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
}
