<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PolarProfileResource;
use App\Models\PolarProfile;
use App\Traits\Api\V1\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ApiResponses;

    public function index(): JsonResponse
    {
        $user = Auth::user();

        $polarProfiles = PolarProfile::where('user_id', $user->id)->get();
        $collection = PolarProfileResource::collection($polarProfiles);

        return $this->success(
            'Profiles retrieved',
            $collection->resolve(),
            200
        );
    }
}
