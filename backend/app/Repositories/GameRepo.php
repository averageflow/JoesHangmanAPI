<?php

namespace App\Repositories;


use App\Models\MyUser;
use App\Repositories\Interfaces\GameRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class GameRepo implements GameRepoInterface
{
    private $userWordsRepo;
    private $usersRepo;

    public function __construct()
    {
        $this->userWordsRepo = new UserWordRepo();
        $this->usersRepo = new MyUserRepo();
    }

    public function respondToGuess(string $letter, string $user): JsonResponse
    {
        $user = $this->usersRepo->getUserByEmail(request('user'));
        $currentID = intval($user["id"]);

        $solution = $this->userWordsRepo->getSolution($currentID);
        $current = $this->userWordsRepo->getFrontEndWord($currentID);

        $lives = $this->userWordsRepo->getLives($currentID);
        $blacklist = explode(" ", $this->userWordsRepo->getBlacklist($currentID));

        return $this->gameEval($lives, $solution, $current, $blacklist, $letter, $user);
    }

    /**
     * Game process evaluation
     *
     * @param integer $lives
     * @param string $solution
     * @param string $current
     * @param array $blacklist
     * @param string $requestedLetter
     * @param MyUser $user
     * @return JsonResponse
     */
    public function gameEval(int $lives, string $solution, string $current, array $blacklist, string $requestedLetter, MyUser $user): JsonResponse
    {
        $formattedBlacklist = implode(' ', $blacklist);
        $currentID = intval($user["id"]);

        if ($lives > 0) {
            $letters = str_split($solution);
            $dashes = str_split($current);

            if (in_array($requestedLetter, $letters)) {
                $dashes = $this->userWordsRepo->replaceGuessedLetters($letters, $requestedLetter, $dashes);
                return $this->userWordsRepo->goodGuessUpdateDB($currentID, $dashes, $solution, $lives, $formattedBlacklist);
            }
            //BAD GUESS
            $lives--;
            array_push($blacklist, $requestedLetter);
            $formattedBlacklist = implode(' ', $blacklist);

            if ($lives > 0) {
                $this->userWordsRepo->badGuessUpdateDB($currentID, $lives, $formattedBlacklist);

                return response()->json([
                    'successGuessing' => false,
                    'lives' => $lives,
                    'currentWord' => $current,
                    'blacklist' => $formattedBlacklist
                ]);
            }
            return $this->userWordsRepo->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
        }
        return $this->userWordsRepo->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
    }

}
