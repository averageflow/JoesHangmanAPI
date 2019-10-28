<?php

namespace App\Repositories\Interfaces;

use App\Models\Users;

interface UserWordsRepoInterface
{
    public function getCurrentWord(string $user);

    public function getRandomWord(string $user);

    public function getUserWordData(string $id);

    public function replaceGuessedLetters(array $letters, string $requestedLetter, array $dashes);

    public function renewUserWords(string $id, array $words, int $lives);

    public function lostGameUpdateDB(string $id, string $formattedBlacklist, int $lives, string $solution);

    public function badGuessUpdateDB(string $id, int $lives, string $formattedBlacklist);

    public function goodGuessUpdateDB(string $id, string $dashes, string $solution, int $lives, string $formattedBlacklist);

    public function wonGameResponse(int $lives, string $formattedBlacklist, string $dashes);

    public function lostGameResponse(Users $user, int $lives, string $formattedBlacklist, string $solution);

}
