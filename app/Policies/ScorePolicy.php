<?php

namespace App\Policies;

use App\Enums\EventStatus;
use App\Enums\UserRole;
use App\Models\Event;
use App\Models\User;

class ScorePolicy
{
    public function create(User $user, Event $event): bool
    {
        return $user->hasRole(UserRole::Judge)
            && $event->judges()->where('judge_id', $user->id)->exists()
            && $event->status === EventStatus::Active;
    }

    public function viewAny(User $user, Event $event): bool
    {
        return $user->hasRole(UserRole::SuperAdmin, UserRole::Admin)
            || ($user->hasRole(UserRole::Judge) && $event->judges()->where('judge_id', $user->id)->exists());
    }
}
