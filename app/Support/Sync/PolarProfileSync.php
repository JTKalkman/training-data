<?php

namespace App\Support\Sync;

use App\Models\PolarProfile;
use App\Models\User;
use App\Support\API\Polar\Resources\PolarExerciseResource;
use App\Support\Parsers\PolarJsonParser;

final class PolarProfileSync extends ProfileSync
{
    protected function profileQuery(User $user)
    {
        return PolarProfile::where('user_id', $user->id);
    }

    protected function fetchExercises(PolarProfile $profile): array
    {
        return PolarExerciseResource::list(decrypt($profile->access_token));
    }

    protected function parser(): object
    {
        return new PolarJsonParser;
    }

    protected function dataSourceName(): string
    {
        return 'polar';
    }

    protected function providerLabel(): string
    {
        return 'Polar';
    }
}
