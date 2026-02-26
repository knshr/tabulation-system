<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::SuperAdmin, UserRole::Admin);
    }

    public function view(User $user, Event $event): bool
    {
        return $user->hasRole(UserRole::SuperAdmin, UserRole::Admin)
            || ($user->hasRole(UserRole::Judge) && $event->judges()->where('judge_id', $user->id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::SuperAdmin, UserRole::Admin);
    }

    public function update(User $user, Event $event): bool
    {
        return $user->hasRole(UserRole::SuperAdmin, UserRole::Admin);
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->hasRole(UserRole::SuperAdmin, UserRole::Admin);
    }
}
