<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SportTypeResource;
use App\Models\SportType;
use App\Traits\Api\V1\ApiResponses;

class SportTypeController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $sportTypes = SportType::all();
        $collection = SportTypeResource::collection($sportTypes);

        return $this->success(
            'Sport types retrieved',
            $collection->resolve(),
            200
        );
    }
}
