<?php

namespace App\Repositories\Interfaces;

use App\Models\MyUser;
use Illuminate\Http\JsonResponse;

interface MyUserScoreRepoInterface
{
    public function getScore(string $user): JsonResponse;

    public function increaseWins(MyUser $user): void;

    public function increaseLosses(MyUser $user): void;

    public function setScore(string $outcome, string $user): JsonResponse;
}
