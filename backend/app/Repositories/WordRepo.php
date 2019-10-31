<?php

namespace App\Repositories;

use App\Models\UserWord;
use App\Models\Word;
use App\Repositories\Interfaces\WordRepoInterface;
use Illuminate\Http\JsonResponse;


class WordRepo implements WordRepoInterface
{
    protected $usersRepo;

    public function __construct()
    {
        $this->usersRepo = new MyUserRepo();
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
        Word::insertNewWord($word, $language);
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
        $randomWord = Word::retrieveRandomWord($userLang);
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
        $userID = $this->usersRepo->getUserByEmail($user)["id"];
        return UserWord::getPreferredLanguage($userID);
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
