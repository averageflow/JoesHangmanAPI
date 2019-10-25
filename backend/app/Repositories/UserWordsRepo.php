<?php

namespace App\Repositories;

use App\Models\UserWords;
use App\Models\Users;
use App\Models\Words;
use App\Repositories\Interfaces\UserWordsRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class UserWordsRepo implements UserWordsRepoInterface
{

    /**
     * Get current DB data for user's word
     *
     * @return JsonResponse
     */
    public function getCurrentWord(string $user): JsonResponse
    {

        $userID = $this->getUserByEmail($user)->id;
        $userWordData = UserWords::where('user_id', '=', $userID)->first();
        if (!$userWordData) {
            return response()->json(['status' => 'No records available!']);
        }
        $currentWord = $userWordData->frontend_word;
        $lives = $userWordData->lives;
        $blacklist = $userWordData->blacklist;
        return response()->json(['word' => $currentWord, 'lives' => $lives, 'blacklist' => $blacklist]);
    }


    /**
     * Insert new word to DB
     *
     * @return JsonResponse
     */
    public function insertNewWord(string $word, string $language): JsonResponse
    {
        Words::insert(['word' => $word, 'language' => $language]);
        return response()->json(['success' => true]);
    }

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
     * Get user's prefered language
     *
     * @param string $user
     * @return void
     */
    public function getUserLang(string $user)
    {
        $userID = $this->getUserByEmail($user)->id;
        return UserWords::select('prefered_language')->where('user_id', '=', $userID)->first()->prefered_language;
    }

    /**
     * Fetch new word and corresponding enigma
     *
     * @param string $user
     * @return array
     */
    public function fetchNewFrontEndWord(string $user): array
    {
        $userLang = $this->getUserLang($user);
        $randomWord = Words::select('word')->where('language', '=', $userLang)->inRandomOrder()->first()->word;

        $frontEndWord = "";

        for ($i = 0; $i < strlen($randomWord); $i++) {
            $frontEndWord .= $this->transformToFrontEndWord($randomWord[$i]);
        }
        return ["solution" => $randomWord, "enigma" => $frontEndWord];
    }


    /**
     * Get random word from list, according to language
     *
     * @param string $user
     * @return JsonResponse
     */
    public function getRandomWord(string $user): JsonResponse
    {
        $words = $this->fetchNewFrontEndWord($user);

        $userID = $this->getUserByEmail($user)->id;
        $this->renewUserWords($userID, $words, 12);
        return response()->json(['word' => $words["enigma"], 'lives' => 12]);

        return response()->json(['error' => 'There was an error fetching a new word!']);
    }
    /**
     * Get user Collection by email
     *
     * @param string $email
     * @return stdClass
     */
    public function getUserByEmail(string $email): Users
    {
        return Users::where('email', '=', $email)->first();
    }

    /**
     * Return user details when ID is provided
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getUserByID(string $id): JsonResponse
    {
        $userDetails = (array) Users::where('id', '=', $id)->first();
        return response()->json($userDetails);
    }

    /**
     * Get user word data for the game
     *
     * @param string $id
     * @return stdClass
     */
    public function getUserWordData(string $id): UserWords
    {
        return UserWords::where('user_id', '=', $id)->first();
    }

    /**
     * Replace dashes by letters in word
     *
     * @param array $letters
     * @param string $requestedLetter
     * @param array $dashes
     * @return string
     */
    public function replaceGuessedLetters(array $letters, string $requestedLetter, array $dashes): string
    {
        $indexes = array_keys($letters, $requestedLetter);
        for ($i = 0; $i < sizeof($indexes); $i++) {
            $dashes[$indexes[$i]] = $requestedLetter;
        }
        $final = implode("", $dashes);
        Log::error("Final frontend word = ".$final);
        return $final;
    }

    /**
     * Renew DB data for user's word
     *
     * @param string $id
     * @param array $words
     * @param int $lives
     * @return void
     */
    public function renewUserWords(string $id, array $words, int $lives): void
    {
        UserWords::updateOrInsert(
            ['user_id' => $id],
            ['word' => $words["solution"], 'frontend_word' => $words["enigma"], 'lives' => $lives, 'blacklist' => '']
        );
    }

    /**
     * Renew DB data for user's word
     *
     * @param string $id
     * @param array $words
     * @param int $lives
     * @return void
     */
    public function lostGameUpdateDB(string $id, string $formattedBlacklist, int $lives, string $solution): void
    {
        UserWords::updateOrInsert(
            ['user_id' => $id],
            ['lives' => $lives, 'blacklist' => $formattedBlacklist, 'frontend_word' => $solution]
        );
    }

    /**
     * Update lives and blacklist on bad guess
     *
     * @param string $id
     * @param integer $lives
     * @param string $formattedBlacklist
     * @return void
     */
    public function badGuessUpdateDB(string $id, int $lives, string $formattedBlacklist): void
    {
        UserWords::updateOrInsert(
            ['user_id' => $id],
            ['lives' => $lives, 'blacklist' => $formattedBlacklist]
        );
    }

    public function goodGuessUpdateDB(string $id, string $dashes, string $solution, int $lives, string $formattedBlacklist){
        UserWords::updateOrInsert(
            ['user_id' => $id],
            ['frontend_word' => $dashes]
        );
        if ($dashes == $solution) {
            //WON THE GAME
            return $this->wonGameResponse($lives, $formattedBlacklist, $dashes);
        }
        return response()->json(['successGuessing' => true, 'lives' => $lives, 'currentWord' => $dashes, 'blacklist' => $formattedBlacklist]);
    }
     /**
     * Return won game response
     *
     * @param integer $lives
     * @param string $formattedBlacklist
     * @param string $dashes
     * @return JsonResponse
     */
    public function wonGameResponse(int $lives, string $formattedBlacklist, string $dashes): JsonResponse
    {
        return response()->json(['victory' => true, 'lives' => $lives, 'successGuessing' => true, 'currentWord' => $dashes, 'blacklist' => $formattedBlacklist]);
    }
    /**
     * Update DB and return lost game response
     *
     * @param stdClass $user
     * @param int $lives
     * @param string $formattedBlacklist
     * @param string $solution
     * @return JsonResponse
     */
    public function lostGameResponse(Users $user, int $lives, string $formattedBlacklist, string $solution): JsonResponse
    {
        $this->lostGameUpdateDB($user->id, $formattedBlacklist, $lives, $solution);
        return response()->json(['victory' => false, 'lives' => 0, 'successGuessing' => true, 'currentWord' => $solution, 'blacklist' => $formattedBlacklist]);
    }
}
