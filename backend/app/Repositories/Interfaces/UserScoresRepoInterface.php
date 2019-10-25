<?php

namespace App\Repositories\Interfaces;

use App\Models\Users;
use App\User;

interface UserScoresRepoInterface
{
    public function getScore(string $user);
    public function increaseWins(Users $user);
    public function increaseLosses(Users $user);
    public function setScore(string $outcome, string $user);
}
