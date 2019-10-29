<?php

namespace App\Repositories;

use App\Models\Words;
use App\Models\UserWords;
use App\Repositories\Interfaces\WordsRepoInterface;
use Illuminate\Http\JsonResponse;


class WordsRepo implements WordsRepoInterface
{
    protected $usersRepo;

    public function __construct()
    {
        $this->usersRepo = new UsersRepo();
    }

    /**
     * Insert new word to DB
     *
     * @param string $word
     * @param string $language
     * @return JsonResponse
     */
    public function insertNewWord(string $word, string $language): JsonResponse
    {
        Words::insertNewWord($word, $language);
        return response()->json(['success' => true]);
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
        $randomWord = Words::retrieveRandomWord($userLang);
        $frontEndWord = "";

        for ($i = 0; $i < strlen($randomWord); $i++) {
            $frontEndWord .= $this->transformToFrontEndWord($randomWord[$i]);
        }
        return ["solution" => $randomWord, "enigma" => $frontEndWord];
    }

    /**
     * Get user's preferred language
     *
     * @param string $user
     * @return string
     */
    public function getUserLang(string $user): string
    {
        $userID = $this->usersRepo->getUserByEmail($user)->id;
        return UserWords::getPreferredLanguage($userID);
    }

    /**
     * Transform word to frontend version enigma
     *
     * @param string $letter
     * @return string
     */
    public static function transformToFrontEndWord(string $letter): string
    {
        if ($letter == "-") {
            return "-";
        }
        if ($letter == " ") {
            return " ";
        }
        return "_";
    }


}
