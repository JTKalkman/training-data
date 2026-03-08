<?php

namespace App\Traits\Api\V1;

use App\Support\DTO\Api\V1\PaginationMeta;
use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function paginated(string $message, array $data, PaginationMeta $meta, int $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'meta' => [
                'currentPage' => $meta->currentPage,
                'lastPage' => $meta->lastPage,
                'perPage' => $meta->perPage,
                'total' => $meta->total,
            ],
            'links' => [
                'next' => $meta->next,
                'prev' => $meta->prev,
            ],
            'statusCode' => $statusCode,
        ]);
    }

    protected function error(string $message, string|array $errors = [], ?int $statusCode = null): JsonResponse
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
