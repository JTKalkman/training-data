<?php

namespace App\Support\DTO\Api\V1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginationMeta
{
    public function __construct(
        public readonly int $currentPage,
        public readonly int $lastPage,
        public readonly int $perPage,
        public readonly int $total,
        public readonly ?string $next,
        public readonly ?string $prev,
    ) {}

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self(
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
            perPage: $paginator->perPage(),
            total: $paginator->total(),
            next: $paginator->nextPageUrl(),
            prev: $paginator->previousPageUrl(),
        );
    }
}
