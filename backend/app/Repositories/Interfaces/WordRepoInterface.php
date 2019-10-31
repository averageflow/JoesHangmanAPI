<?php

namespace App\Repositories\Interfaces;


use Illuminate\Http\JsonResponse;

interface WordRepoInterface
{
    public function insertNewWord(string $word, string $language):JsonResponse;

    public function getUserLang(string $user):string;

    public function fetchNewFrontEndWord(string $user):array;

    public static function transformToFrontEndWord(string $letter): string;
}
