<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\WordsModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class WordController extends Controller
{
    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return void
     */
    public function getUserByEmail($email)
    {
        return DB::table('users')->where('email', '=', $email)->first();
    }
    /**
     * Undocumented function
     *
     * @param [type] $letter
     * @return string
     */
    public function transformToFrontEndWord($letter): string
    {
        if ($letter == "-") {
            return "-";
        }
        if ($letter == " ") {
            return " ";
        }
        return "_";
    }

    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function getRandomWord(): JsonResponse
    {
        $randomWord = WordsModel::select('word')->where('language', '=', request('language'))->inRandomOrder()->first()->word;

        $frontEndWord = "";

        for ($i = 0; $i < strlen($randomWord); $i++) {
            $frontEndWord .= $this->transformToFrontEndWord($randomWord[$i]);
        }

        if (request('newWord') == true && request('user')) {
            $user = $this->getUserByEmail(request('user'));
            DB::table('users_words')->updateOrInsert(
                ['user_id' => $user->id],
                ['word' => $randomWord, 'frontend_word' => $frontEndWord, 'lives' => 12, 'blacklist' => '']
            );
            return response()->json(['word' => $frontEndWord, 'lives' => 12]);
        }
        return response()->json(['error' => 'There was an error fetching a new word!']);
    }
    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function getCurrentWord(): JsonResponse
    {
        if (request('newWord') == false && request('user')) {
            $user = $this->getUserByEmail(request('user'));
            $userWordData = DB::table('users_words')->where('user_id', '=', $user->id)->first();
            if (!$userWordData) {
                return response()->json(['status' => 'No records available!']);
            }
            $currentWord = $userWordData->frontend_word;
            $lives = $userWordData->lives;
            $blacklist = $userWordData->blacklist;
            return response()->json(['word' => $currentWord, 'lives' => $lives, 'blacklist' => $blacklist]);
        }
    }
    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function insertNewWord(): JsonResponse
    {
        if (request('word') && request('language')) {
            DB::table('words')->insert(['word' => request('word'), 'language' => request('language')]);
            return response()->json(['success' => true]);
        }
    }
}
