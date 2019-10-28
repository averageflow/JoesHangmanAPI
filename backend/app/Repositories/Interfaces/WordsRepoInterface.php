<?php

namespace App\Repositories\Interfaces;


interface WordsRepoInterface
{
    public function insertNewWord(string $word, string $language);

    public function getUserLang(string $user);

    public function transformToFrontEndWord(string $letter);

    public function fetchNewFrontEndWord(string $user);
}
