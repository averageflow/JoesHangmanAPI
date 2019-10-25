<?php

namespace App\Repositories\Interfaces;

interface WordsRepoInterface
{
    public function insertNewWord();
    public function getCurrentWord($userID);
    public function getRandomWord();

}
