<?php

namespace App\Policies;

use App\Models\TrainingSession;
use App\Models\User;

class TrainingSessionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TrainingSession $trainingSession): bool
    {
        return $user->id === $trainingSession->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TrainingSession $trainingSession): bool
    {
        return $user->id === $trainingSession->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TrainingSession $trainingSession): bool
    {
        return $user->id === $trainingSession->user_id;
    }
}
