<?php

namespace App\Repositories;


use App\Models\Users;
use App\Repositories\Interfaces\GameRepoInterface;
use Illuminate\Http\JsonResponse;


class GameRepo implements GameRepoInterface
{
    private $userWordsRepo;
    private $usersRepo;

    public function __construct()
    {
        $this->userWordsRepo = new UserWordsRepo();
        $this->usersRepo = new UsersRepo();
    }

    public function respondToGuess(string $letter, string $user)
    {
        $user = $this->usersRepo->getUserByEmail(request('user'));

        $solution = $this->userWordsRepo->getSolution($user->id);
        $current = $this->userWordsRepo->getFrontEndWord($user->id);

        $lives = $this->userWordsRepo->getLives($user->id);
        $blacklist = explode(" ", $this->userWordsRepo->getBlacklist($user->id));
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
     * @param Users $user
     * @return JsonResponse
     */
    public function gameEval(int $lives, string $solution, string $current, array $blacklist, string $requestedLetter, Users $user): JsonResponse
    {
        $formattedBlacklist = implode(' ', $blacklist);

        if ($lives > 0) {
            $letters = str_split($solution);
            $dashes = str_split($current);

            if (in_array($requestedLetter, $letters)) {
                $dashes = $this->userWordsRepo->replaceGuessedLetters($letters, $requestedLetter, $dashes);
                return $this->userWordsRepo->goodGuessUpdateDB($user->id, $dashes, $solution, $lives, $formattedBlacklist);
            }
            //BAD GUESS
            $lives--;
            array_push($blacklist, $requestedLetter);
            $formattedBlacklist = implode(' ', $blacklist);
            if ($lives > 0) {
                $this->userWordsRepo->badGuessUpdateDB($user->id, $lives, $formattedBlacklist);

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
