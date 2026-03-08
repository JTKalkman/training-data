<?php

namespace App\Http\Filters\Api\V1;

use Illuminate\Database\Eloquent\Builder;

class TrainingSessionFilter extends QueryFilter
{
    public function from($value): Builder
    {
        return $this->builder->where('started_at', '>=', $value);
    }

    public function to($value): Builder
    {
        return $this->builder->where('started_at', '<=', $value);
    }

    public function sport($value): Builder
    {
        return $this->builder->whereHas('sportType', function($query) use ($value) {
            $query->where('name', $value);
        });
    }
}
