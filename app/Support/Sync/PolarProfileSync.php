<?php

namespace App\Support\Sync;

use App\Models\PolarProfile;
use App\Models\SyncProfile;
use App\Models\User;
use App\Support\API\Polar\Resources\PolarExerciseResource;
use App\Support\Parsers\PolarApiParser;

use Illuminate\Database\Eloquent\Builder;

final class PolarProfileSync extends ProfileSync
{
    protected function profileQuery(User $user): Builder
    {
        return PolarProfile::where('user_id', $user->id);
    }

    protected function fetchExercises(SyncProfile $profile): array
    {
        return PolarExerciseResource::list(decrypt($profile->access_token));
    }

    protected function parser(): object
    {
        return new PolarApiParser;
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
