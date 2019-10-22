<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\WordsModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\CommonUtils;

class WordController extends Controller
{
    protected $commonUtils;

    /**
     * Transform word to frontend version enigma
     *
     * @param string $letter
     * @return string
     */
    public function transformToFrontEndWord(string $letter): string
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
     * Fetch new word and corresponding enigma
     *
     * @return array
     */
    public function fetchNewFrontEndWord(): array
    {
        $randomWord = WordsModel::select('word')->where('language', '=', request('language'))->inRandomOrder()->first()->word;

        $frontEndWord = "";

        for ($i = 0; $i < strlen($randomWord); $i++) {
            $frontEndWord .= $this->transformToFrontEndWord($randomWord[$i]);
        }
        return ["solution" => $randomWord, "enigma" => $frontEndWord];
    }


    /**
     * Get random word from list, according to language
     *
     * @return JsonResponse
     */
    public function getRandomWord(): JsonResponse
    {
        $words = $this->fetchNewFrontEndWord();

        if (request('newWord') == true && request('user')) {
            $user = $this->commonUtils->getUserByEmail(request('user'));
            $this->commonUtils->renewUserWords($user, $words, 12);
            return response()->json(['word' => $words["enigma"], 'lives' => 12]);
        }
        return response()->json(['error' => 'There was an error fetching a new word!']);
    }

    /**
     * Get current DB data for user's word
     *
     * @return JsonResponse
     */
    public function getCurrentWord(): JsonResponse
    {
        if (request('newWord') == false && request('user')) {
            $user = $this->commonUtils->getUserByEmail(request('user'));
            $userWordData = DB::table('users_words')->where('user_id', '=', $user->id)->first();
            if (!$userWordData) {
                return response()->json(['status' => 'No records available!']);
            }
            $currentWord = $userWordData->frontend_word;
            $lives = $userWordData->lives;
            $blacklist = $userWordData->blacklist;
            return response()->json(['word' => $currentWord, 'lives' => $lives, 'blacklist' => $blacklist]);
        }
        return response()->json(['error' => "There was an error fetching user's word!"]);
    }

    /**
     * Insert new word to DB
     *
     * @return JsonResponse
     */
    public function insertNewWord(): JsonResponse
    {
        if (request('word') && request('language')) {
            DB::table('words')->insert(['word' => request('word'), 'language' => request('language')]);
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'There was an error inserting a new word!']);
    }

    public function __construct()
    {
        $this->commonUtils = new CommonUtils();
    }
}
