<?php

use App\Services\Api\ApiResponseHandler;
use Illuminate\Http\JsonResponse;

function success($info = [], $message = 'Success', $code = 200): JsonResponse
{
    return (new ApiResponseHandler())->success($info, $message, $code);
}

function fail($errors = [], $message = 'Error', $code = 400): JsonResponse
{
    return (new ApiResponseHandler())->error($errors, $message, $code);
}
