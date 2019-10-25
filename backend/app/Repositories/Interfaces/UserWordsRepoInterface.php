<?php

namespace App\Repositories\Interfaces;

use App\User;

interface UserWordsRepoInterface
{
    public function all();

    public function getByUser(User $user);
}
