<?php

namespace App\Repositories;

use App\Models\UserScores;
use App\User;
use App\Repositories\Interfaces\UserScoresRepoInterface;

class UserRepo implements UserScoresRepoInterface
{
    public function all()
    {
        return UserScores::all();
    }

    public function getByUser(User $user)
    {
        return UserScores::where('user_id' . $user->id)->get();
    }
}
