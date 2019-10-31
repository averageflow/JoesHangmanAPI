<?php

namespace App\Repositories;

use App\Models\UserWord;
use App\Models\MyUser;

use App\Repositories\Interfaces\UserWordRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserWordRepo implements UserWordRepoInterface
{
    protected $wordsRepo;
    protected $usersRepo;

    public function __construct()
    {
        $this->wordsRepo = new WordRepo();
        $this->usersRepo = new MyUserRepo();
    }

    /**
     * Get current DB data for user's word
     *
     * @param string $user
     * @return JsonResponse
     */
    public function getCurrentWord(string $user): JsonResponse
    {

        $user = $this->usersRepo->getUserByEmail($user);

        $currentID = intval($user["id"]);

        Log::error($currentID);

        $currentWord = UserWord::getFrontendWord($currentID);
        $lives = UserWord::getLives($currentID);
        $blacklist = UserWord::getBlacklist($currentID);

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
        if ($words) {
            $userID = intval($this->usersRepo->getUserByEmail($user)["id"]);
            $this->renewUserWords($userID, $words, 12);
            return response()->json(['word' => $words["enigma"], 'lives' => 12]);
        }
        return response()->json(['error' => 'There was an error fetching a new word!']);

    }

    /**
     * Renew DB data for user's word
     *
     * @param int $id
     * @param array $words
     * @param int $lives
     * @return void
     */
    public function renewUserWords(int $id, array $words, int $lives): void
    {
        UserWord::renewUserWords($id, $words, $lives);
    }

    /**
     * Get user word data for the game
     *
     * @param int $id
     * @return UserWord
     */
    public function getUserWordData(int $id): UserWord
    {
        return UserWord::getUserWordData($id);
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
     * @param int $id
     * @param integer $lives
     * @param string $formattedBlacklist
     * @return void
     */
    public function badGuessUpdateDB(int $id, int $lives, string $formattedBlacklist): void
    {
        (new UserWord)->updateOrInsert(
            ['user_id' => $id],
            ['lives' => $lives, 'blacklist' => $formattedBlacklist]
        );
    }

    public function goodGuessUpdateDB(int $id, string $dashes, string $solution, int $lives, string $formattedBlacklist): JsonResponse
    {
        (new UserWord)->updateOrInsert(
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
     * @param MyUser $user
     * @param int $lives
     * @param string $formattedBlacklist
     * @param string $solution
     * @return JsonResponse
     */
    public function lostGameResponse(MyUser $user, int $lives, string $formattedBlacklist, string $solution): JsonResponse
    {
        $this->lostGameUpdateDB(intval($user->id), $formattedBlacklist, $lives, $solution);
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
     * @param int $id
     * @param string $formattedBlacklist
     * @param int $lives
     * @param string $solution
     * @return void
     */
    public function lostGameUpdateDB(int $id, string $formattedBlacklist, int $lives, string $solution): void
    {
        (new UserWord)->updateOrInsert(
            ['user_id' => $id],
            [
                'lives' => $lives,
                'blacklist' => $formattedBlacklist,
                'frontend_word' => $solution
            ]
        );
    }

    public function getSolution(int $id): string
    {
        return UserWord::getSolution($id);
    }

    public function getFrontEndWord(int $id): string
    {
        return UserWord::getFrontendWord($id);
    }

    public function getLives(int $id): int
    {
        return intval(UserWord::getLives($id));
    }

    public function getBlacklist(int $id): string
    {
        return UserWord::getBlacklist($id);
    }
}
