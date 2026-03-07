<?php

namespace App\Traits\Api\V1;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function error(string $message, string|array $errors = [], int|null $statusCode = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    protected function success(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
