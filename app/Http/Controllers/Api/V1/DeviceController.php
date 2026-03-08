<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DeviceResource;
use App\Models\Device;
use App\Traits\Api\V1\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    use ApiResponses;

    public function index(): JsonResponse
    {
        $user = Auth::user();

        $polarProfiles = Device::where('user_id', $user->id)->with('dataSource')->get();
        $collection = DeviceResource::collection($polarProfiles);

        return $this->success(
            'Devices retrieved',
            $collection->resolve(),
            200
        );
    }
}
