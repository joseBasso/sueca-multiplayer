<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->admin == 1) {
            return true;
        }
    }

    public function self(User $authUser, User $user)
    {
        return $authUser->id == $user->id;
    }

    public function administrate(User $authUser){
        return $authUser->isAdmin();
    }
}
