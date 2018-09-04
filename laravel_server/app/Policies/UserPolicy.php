<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->admin == 1;
    }

    public function self(User $authUser, User $user)
    {
        return $authUser->id == $user->id;
    }

    public function administrate(User $authUser){
        return $authUser->admin == 1;
    }
}
