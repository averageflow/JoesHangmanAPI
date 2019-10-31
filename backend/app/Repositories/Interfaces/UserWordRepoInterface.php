<?php

namespace App\Repositories\Interfaces;

use App\Models\MyUser;
use App\Models\UserWord;
use Illuminate\Http\JsonResponse;

interface UserWordRepoInterface
{
    public function getCurrentWord(string $user): JsonResponse;

    public function getRandomWord(string $user): JsonResponse;

    public function getUserWordData(int $id): UserWord;

    public function replaceGuessedLetters(array $letters, string $requestedLetter, array $dashes): string;

    public function renewUserWords(int $id, array $words, int $lives): void;

    public function lostGameUpdateDB(int $id, string $formattedBlacklist, int $lives, string $solution): void;

    public function badGuessUpdateDB(int $id, int $lives, string $formattedBlacklist): void;

    public function goodGuessUpdateDB(int $id, string $dashes, string $solution, int $lives, string $formattedBlacklist): JsonResponse;

    public function wonGameResponse(int $lives, string $formattedBlacklist, string $dashes): JsonResponse;

    public function lostGameResponse(MyUser $user, int $lives, string $formattedBlacklist, string $solution): JsonResponse;

    public function getSolution(int $id): string;

    public function getLives(int $id): int;

    public function getFrontEndWord(int $id): string;

    public function getBlacklist(int $id): string;

}
