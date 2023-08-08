<?php

namespace App\Services\Api;

use Illuminate\Http\JsonResponse;

class ApiResponseHandler
{
    private array $data = [];

    public function success($info = null, $message = 'Success', $code = 200): JsonResponse
    {
        if (! empty($message)) {
            $this->setMessage($message);
        }

        if (is_int($code)) {
            $this->setCode($code);
        }

        if ($info) {
            $this->setInfo($info);
        }

        return response()->json($this->data, $code);
    }

    public function error($errors = [], $message = 'Error', $code = 400): JsonResponse
    {
        if (! empty($message)) {
            $this->setMessage($message);
        }

        if (is_int($code)) {
            $this->setCode($code);
        }

        if ($errors) {
            $this->setErrors($errors);
        }

        return response()->json($this->data, $code);
    }

    public function setCode(int $code): void
    {
        $this->data['code'] = $code;

    }

    public function setMessage(string $message): void
    {
        $this->data['message'] = $message;
    }

    public function setErrors($errors): void
    {
        $this->data['errors'] = $errors;
    }

    public function setInfo($info): void
    {
        $this->data['data'] = $info;
    }
}
