<?php

namespace App\Traits\Api\V1;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function paginated($message, $collection, $data, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'data' => $collection->resolve(),
            'meta' => [
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'perPage' => $data->perPage(),
                'total' => $data->total(),
            ],
            'links' => [
                'next' => $data->nextPageUrl(),
                'prev' => $data->previousPageUrl(),
            ],
            'statusCode' => $statusCode
        ]);
    }

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
