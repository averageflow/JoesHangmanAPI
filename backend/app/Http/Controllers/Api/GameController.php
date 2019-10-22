<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Controls the game flow based on guesses
 */

class GameController extends Controller
{
    /**
     * Game process evaluation
     *
     * @param integer $lives
     * @param string $solution
     * @param string $current
     * @param array $blacklist
     * @param string $requestedLetter
     * @param stdClass $user
     * @return JsonResponse
     */
    public function gameEval(int $lives, string $solution, string $current, array $blacklist, string $requestedLetter, stdClass $user): JsonResponse
    {
        $formattedBlacklist =  implode(' ', $blacklist);

        if ($lives > 0) {
            $letters = str_split($solution);
            $dashes = str_split($current);

            if (in_array($requestedLetter, $letters)) {
                //GOOD GUESS
                $dashes = $this->commonUtils->replaceGuessedLetters($letters, $requestedLetter, $dashes);

                DB::table('users_words')->updateOrInsert(
                    ['user_id' => $user->id],
                    ['frontend_word' => $dashes]
                );
                if ($dashes == $solution) {
                    //WON THE GAME
                    return $this->wonGameResponse($lives, $formattedBlacklist, $dashes);
                }
                return response()->json(['successGuessing' => true, 'lives' => $lives, 'currentWord' => $dashes, 'blacklist' => $formattedBlacklist]);
            }
            //BAD GUESS
            $lives--;
            array_push($blacklist, $requestedLetter);
            $formattedBlacklist =  implode(' ', $blacklist);
            if ($lives > 0) {
                $user = $this->commonUtils->getUserByEmail(request('user'));
                $this->commonUtils->badGuessUpdateDB($user, $lives, $formattedBlacklist);

                return response()->json(['successGuessing' => false, 'lives' => $lives, 'currentWord' => $current, 'blacklist' => $formattedBlacklist]);
            }
            return $this->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
        }
        return $this->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
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
    public function lostGameResponse(stdClass $user, int $lives, string $formattedBlacklist, string $solution): JsonResponse
    {
        $this->commonUtils->lostGameUpdateDB($user, $formattedBlacklist, $lives, $solution);
        return response()->json(['victory' => false, 'lives' => 0, 'successGuessing' => true, 'currentWord' => $solution, 'blacklist' => $formattedBlacklist]);
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
     * Respond to a letter guess
     *
     * @return JsonResponse
     */
    public function respondToGuess(): JsonResponse
    {
        if (request('letter') && request('user')) {

            $requestedLetter = strtoupper(request('letter'));
            $user = $this->commonUtils->getUserByEmail(request('user'));
            $usersWordData = $this->commonUtils->getUserWordData($user);

            $solution = $usersWordData->word;
            $current = $usersWordData->frontend_word;

            $lives = intval($usersWordData->lives);
            $blacklist = explode(" ", $usersWordData->blacklist);

            return $this->gameEval($lives, $solution, $current, $blacklist, $requestedLetter, $user);
        }
    }

    public function __construct()
    {
        $this->commonUtils = new CommonUtils();
    }
}
