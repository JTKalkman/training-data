<?php

namespace App\Traits\Api\V1;

trait ApiResponses
{
    protected function error(string $message, string|array $errors = [], int|null $statusCode = null): string
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    protected function success(string $message, array $data = [], int $statusCode = 200): string
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
