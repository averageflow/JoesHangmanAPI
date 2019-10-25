<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Repositories\Interfaces\UsersRepoInterface;
use Illuminate\Http\JsonResponse;
use App\Repositories\Interfaces\UserWordsRepoInterface;

/**
 * Controls the game flow based on guesses
 */

class GameController extends Controller
{
    private $userWordsRepo;
    private $usersRepo;

    public function __construct(UserWordsRepoInterface $userWordsRepo, UsersRepoInterface $usersRepo)
    {
        $this->userWordsRepo = $userWordsRepo;
        $this->usersRepo = $usersRepo;
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
        $formattedBlacklist =  implode(' ', $blacklist);

        if ($lives > 0) {
            $letters = str_split($solution);
            $dashes = str_split($current);

            if (in_array($requestedLetter, $letters)) {
                //Log::error("GOOD GUESS");
                $dashes = $this->userWordsRepo->replaceGuessedLetters($letters, $requestedLetter, $dashes);

                return $this->userWordsRepo->goodGuessUpdateDB($user->id, $dashes, $solution, $lives, $formattedBlacklist);
            }
            //BAD GUESS
            $lives--;
            array_push($blacklist, $requestedLetter);
            $formattedBlacklist =  implode(' ', $blacklist);
            if ($lives > 0) {
                $this->userWordsRepo->badGuessUpdateDB($user->id, $lives, $formattedBlacklist);

                return response()->json(['successGuessing' => false, 'lives' => $lives, 'currentWord' => $current, 'blacklist' => $formattedBlacklist]);
            }
            return $this->userWordsRepo->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
        }
        return $this->userWordsRepo->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
    }

    public function sanitizeRequestedLetter(string $str): string
    {
        if($str == "0"){
            return "A";
        }
        return trim(strtoupper($str));
     }

    /**
     * Respond to a letter guess
     *
     * @return JsonResponse
     */
    public function respondToGuess(): JsonResponse
    {
        $requestedLetter = $this->sanitizeRequestedLetter(request('letter'));
        if ($requestedLetter && request('user')) {

            $user = $this->usersRepo->getUserByEmail(request('user'));
            $usersWordData = $this->userWordsRepo->getUserWordData($user->id);

            $solution = $usersWordData->word;
            $current = $usersWordData->frontend_word;

            $lives = intval($usersWordData->lives);
            $blacklist = explode(" ", $usersWordData->blacklist);
            return $this->gameEval($lives, $solution, $current, $blacklist, $requestedLetter, $user);
        }
    }


}
