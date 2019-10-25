<?php

namespace App\Repositories\Interfaces;

use App\User;

interface UserScoresRepoInterface
{
    public function all();

    public function getByUser(User $user);
}
