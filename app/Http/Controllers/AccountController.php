<?php

namespace App\Http\Controllers;

use App\Http\Resources\PolarProfileResource;
use App\Models\PolarProfile;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        $polarProfiles = PolarProfile::where('user_id', $user->id)
            ->get();

        return Inertia::render('Account/Settings', [
            'polar_profiles' => PolarProfileResource::collection($polarProfiles),
        ]);
    }
}
