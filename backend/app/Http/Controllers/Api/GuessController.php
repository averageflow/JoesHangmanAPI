<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GuessController extends Controller
{
    public function respondToGuess()
    {
        if (request('letter') !== null && request('user') !== null) {
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
                } else {
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
                    //LOST THE GAME
                    DB::table('users_words')->updateOrInsert(
                        ['user_id' => $user->id],
                        ['lives' => $lives, 'blacklist' => $formattedBlacklist, 'frontend_word'=>$solution]
                    );
                    return response()->json(['victory' => false, 'lives' => 0, 'successGuessing' => true, 'currentWord' => $solution, 'blacklist' => $formattedBlacklist]);
                }
            } else {
                //LOST THE GAME
                DB::table('users_words')->updateOrInsert(
                    ['user_id' => $user->id],
                    ['lives' => $lives, 'blacklist' => $formattedBlacklist, 'frontend_word'=>$solution]
                );
                return response()->json(['victory' => false, 'lives' => 0, 'successGuessing' => true, 'currentWord' => $solution, 'blacklist' => $formattedBlacklist]);
            }


            return response()->json(['successGuessing' => false, 'currentWord' => $solution, 'lives' => $lives, 'currentWord' => $current]);
        }
    }
}
