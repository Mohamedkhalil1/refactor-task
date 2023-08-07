<?php

namespace App\Facades\Context;
use App\Services\General\PaginationService;

class ApiResponse
{
    public function success($code = 200, $data = [], $message = 'Successful', $pagination = null)
    {
        $pagination = $pagination !== null ? PaginationService::paginate($pagination) : null;

        return response()->json([
            'data' => $data,
            'message' => $message,
            'pagination' => $pagination,
        ], $code);
    }

    public function error($code = 400, $errors = [], $message = '')
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ], $code);
    }

}
