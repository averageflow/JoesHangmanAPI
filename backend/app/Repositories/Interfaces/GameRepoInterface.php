<?php

namespace App\Repositories\Interfaces;

use App\Models\MyUser;
use Illuminate\Http\JsonResponse;

interface GameRepoInterface
{
    public function respondToGuess(string $letter, string $user): JsonResponse;

    public function gameEval(int $lives, string $solution, string $current, array $blacklist, string $requestedLetter, MyUser $user): JsonResponse;

}
