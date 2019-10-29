<?php

namespace App\Repositories\Interfaces;

use App\Models\Users;

interface GameRepoInterface
{
    public function respondToGuess(string $letter, string $user);

    public function gameEval(int $lives, string $solution, string $current, array $blacklist, string $requestedLetter, Users $user);

}
