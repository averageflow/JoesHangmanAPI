<?php

namespace App\Repositories;

use App\Models\Words;
use App\Repositories\Interfaces\WordsRepoInterface;
use Illuminate\Support\Facades\Response;


class WordsRepo implements WordsRepoInterface
{
    public function insertNewWord()
    { }
    public function getCurrentWord($userID)
    {
        $userWordData = DB::table('users_words')->where('user_id', '=', $userID)->first();
        if (!$userWordData) {
            return response()->json(['status' => 'No records available!']);
        }
        $currentWord = $userWordData->frontend_word;
        $lives = $userWordData->lives;
        $blacklist = $userWordData->blacklist;
        return response()->json(['word' => $currentWord, 'lives' => $lives, 'blacklist' => $blacklist]);
    }
    public function getRandomWord()
    { }
}
