<?php

namespace App\Repositories;

use App\Models\UserWords;
use App\Models\Users;

use App\Repositories\Interfaces\UserWordsRepoInterface;
use Illuminate\Http\JsonResponse;

class UserWordsRepo implements UserWordsRepoInterface
{
    protected $wordsRepo;
    protected $usersRepo;

    public function __construct()
    {
        $this->wordsRepo = new WordsRepo();
        $this->usersRepo = new UsersRepo();
    }

    /**
     * Get current DB data for user's word
     *
     * @param string $user
     * @return JsonResponse
     */
    public function getCurrentWord(string $user): JsonResponse
    {

        $userID = $this->usersRepo->getUserByEmail($user)->id;

        $currentWord = UserWords::getFrontendWord($userID);
        $lives = UserWords::getLives($userID);
        $blacklist = UserWords::getBlacklist($userID);

        if (!$currentWord || !$lives || !$blacklist) {
            return response()->json(['status' => 'No records available!']);
        }

        return response()->json(['word' => $currentWord, 'lives' => $lives, 'blacklist' => $blacklist]);
    }


    /**
     * Get random word from list, according to language
     *
     * @param string $user
     * @return JsonResponse
     */
    public function getRandomWord(string $user): JsonResponse
    {
        $words = $this->wordsRepo->fetchNewFrontEndWord($user);

        $userID = $this->usersRepo->getUserByEmail($user)->id;
        $this->renewUserWords($userID, $words, 12);
        return response()->json(['word' => $words["enigma"], 'lives' => 12]);

        //  return response()->json(['error' => 'There was an error fetching a new word!']);
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
        UserWords::renewUserWords($id, $words, $lives);
    }

    /**
     * Get user word data for the game
     *
     * @param string $id
     * @return UserWords
     */
    public function getUserWordData(string $id): UserWords
    {
        return UserWords::getUserWordData($id);
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
        //Log::error("Final frontend word = ".$final);
        return $final;
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
        (new UserWords)->updateOrInsert(
            ['user_id' => $id],
            ['lives' => $lives, 'blacklist' => $formattedBlacklist]
        );
    }

    public function goodGuessUpdateDB(string $id, string $dashes, string $solution, int $lives, string $formattedBlacklist)
    {
        (new UserWords)->updateOrInsert(
            ['user_id' => $id],
            ['frontend_word' => $dashes]
        );
        if ($dashes == $solution) {
            //WON THE GAME
            return $this->wonGameResponse($lives, $formattedBlacklist, $dashes);
        }
        return response()->json([
            'successGuessing' => true,
            'lives' => $lives,
            'currentWord' => $dashes,
            'blacklist' => $formattedBlacklist
        ]);
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
        return response()->json([
            'victory' => true,
            'lives' => $lives,
            'successGuessing' => true,
            'currentWord' => $dashes,
            'blacklist' => $formattedBlacklist
        ]);
    }

    /**
     * Update DB and return lost game response
     *
     * @param Users $user
     * @param int $lives
     * @param string $formattedBlacklist
     * @param string $solution
     * @return JsonResponse
     */
    public function lostGameResponse(Users $user, int $lives, string $formattedBlacklist, string $solution): JsonResponse
    {
        $this->lostGameUpdateDB($user->id, $formattedBlacklist, $lives, $solution);
        return response()->json([
            'victory' => false,
            'lives' => 0,
            'successGuessing' => true,
            'currentWord' => $solution,
            'blacklist' => $formattedBlacklist
        ]);
    }

    /**
     * Renew DB data for user's word
     *
     * @param string $id
     * @param string $formattedBlacklist
     * @param int $lives
     * @param string $solution
     * @return void
     */
    public function lostGameUpdateDB(string $id, string $formattedBlacklist, int $lives, string $solution): void
    {
        (new UserWords)->updateOrInsert(
            ['user_id' => $id],
            [
                'lives' => $lives,
                'blacklist' => $formattedBlacklist,
                'frontend_word' => $solution
            ]
        );
    }

    public function getSolution($id)
    {
        return UserWords::getSolution($id);
    }

    public function getFrontEndWord($id)
    {
        return UserWords::getFrontendWord($id);
    }

    public function getLives($id)
    {
        return intval(UserWords::getLives($id));
    }

    public function getBlacklist($id)
    {
        return UserWords::getBlacklist($id);
    }
}
