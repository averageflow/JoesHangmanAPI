<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GuessController extends Controller
{
    /**
     * Undocumented function
     *
     * @param [type] $user
     * @param [type] $lives
     * @param [type] $formattedBlacklist
     * @param [type] $solution
     * @return JsonResponse
     */
    public function lostGameResponse($user, $lives, $formattedBlacklist, $solution): JsonResponse
    {
        DB::table('users_words')->updateOrInsert(
            ['user_id' => $user->id],
            ['lives' => $lives, 'blacklist' => $formattedBlacklist, 'frontend_word' => $solution]
        );
        return response()->json(['victory' => false, 'lives' => 0, 'successGuessing' => true, 'currentWord' => $solution, 'blacklist' => $formattedBlacklist]);
    }
    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function respondToGuess(): JsonResponse
    {
        if (request('letter') && request('user')) {
            $requestedLetter = strtoupper(request('letter'));
            $user = DB::table('users')->where('email', '=', request('user'))->first();
            $usersWordData = DB::table('users_words')->where('user_id', '=', $user->id)->first();
            $solution = $usersWordData->word;
            $current = $usersWordData->frontend_word;
            $lives = intval($usersWordData->lives);
            $blacklist = explode(" ", $usersWordData->blacklist);

            if ($lives > 0) {
                $letters = str_split($solution);
                $dashes = str_split($current);
                $formattedBlacklist =  implode(' ', $blacklist);

                if (in_array($requestedLetter, $letters)) {
                    //GOOD GUESS
                    $indexes = array_keys($letters, $requestedLetter);
                    for ($i = 0; $i < sizeof($indexes); $i++) {
                        $dashes[$indexes[$i]] = $requestedLetter;
                    }
                    $dashes = implode("", $dashes);
                    DB::table('users_words')->updateOrInsert(
                        ['user_id' => $user->id],
                        ['frontend_word' => $dashes]
                    );
                    if ($dashes == $solution) {
                        return response()->json(['victory' => true, 'lives' => $lives, 'successGuessing' => true, 'currentWord' => $dashes, 'blacklist' => $formattedBlacklist]);
                    }
                    return response()->json(['successGuessing' => true, 'lives' => $lives, 'currentWord' => $dashes, 'blacklist' => $formattedBlacklist]);
                }
                //BAD GUESS
                $lives--;
                array_push($blacklist, $requestedLetter);
                $formattedBlacklist =  implode(' ', $blacklist);
                if ($lives > 0) {
                    $user = DB::table('users')->where('email', '=', request('user'))->first();

                    DB::table('users_words')->updateOrInsert(
                        ['user_id' => $user->id],
                        ['lives' => $lives, 'blacklist' => $formattedBlacklist]
                    );
                    return response()->json(['successGuessing' => false, 'lives' => $lives, 'currentWord' => $current, 'blacklist' => $formattedBlacklist]);
                }
                return $this->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
            } else {
                return $this->lostGameResponse($user, $lives, $formattedBlacklist, $solution);
            }

            return response()->json(['successGuessing' => false, 'currentWord' => $solution, 'lives' => $lives, 'currentWord' => $current]);
        }
    }
}
