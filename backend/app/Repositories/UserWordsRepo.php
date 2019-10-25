<?php

namespace App\Repositories;

use App\Models\UserWords;
use App\User;
use App\Repositories\Interfaces\UserWordsRepoInterface;

class UserWordsRepo implements UserWordsRepoInterface
{
    public function all()
    {
        return UserWords::all();
    }

    public function getByUser(User $user)
    {
        return UserWords::where('user_id'. $user->id)->get();
    }
}
