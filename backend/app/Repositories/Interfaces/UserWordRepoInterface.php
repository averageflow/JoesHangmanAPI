<?php

namespace App\Repositories\Interfaces;

use App\Models\MyUser;
use App\Models\UserWord;
use Illuminate\Http\JsonResponse;

interface UserWordRepoInterface
{
    public function getCurrentWord(string $userEmail): JsonResponse;

    public function getRandomWord(string $userEmail): JsonResponse;

    public function getUserWordData(int $userID): UserWord;

    public function replaceGuessedLetters(array $letters, string $requestedLetter, array $dashes): string;

    public function renewUserWords(int $userID, array $words, int $lives): void;

    public function lostGameUpdateDB(int $userID, string $formattedBlacklist, int $lives, string $solution): void;

    public function badGuessUpdateDB(int $userID, int $lives, string $formattedBlacklist): void;

    public function goodGuessUpdateDB(int $userID, string $dashes, string $solution, int $lives, string $formattedBlacklist): JsonResponse;

    public function wonGameResponse(int $lives, string $formattedBlacklist, string $dashes): JsonResponse;

    public function lostGameResponse(MyUser $user, int $lives, string $formattedBlacklist, string $solution): JsonResponse;

    public function getSolution(int $userID): string;

    public function getLives(int $userID): int;

    public function getFrontEndWord(int $userID): string;

    public function getBlacklist(int $userID): string;

}
