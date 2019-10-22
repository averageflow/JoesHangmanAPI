<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Common utility class
 */
class CommonUtils
{
    /**
     * Get user Collection by email
     *
     * @param string $email
     * @return stdClass
     */
    public function getUserByEmail(string $email): stdClass
    {
        return DB::table('users')->where('email', '=', $email)->first();
    }

    /**
     * Get user word data for the game
     *
     * @param stdClass $user
     * @return stdClass
     */
    public function getUserWordData(stdClass $user): stdClass
    {
        return DB::table('users_words')->where('user_id', '=', $user->id)->first();
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
        return implode("", $dashes);
    }

    /**
     * Renew DB data for user's word
     *
     * @param stdClass $user
     * @param array $words
     * @param int $lives
     * @return void
     */
    public function renewUserWords(stdClass $user, array $words, int $lives): void
    {
        DB::table('users_words')->updateOrInsert(
            ['user_id' => $user->id],
            ['word' => $words["solution"], 'frontend_word' => $words["enigma"], 'lives' => $lives, 'blacklist' => '']
        );
    }

    /**
     * Renew DB data for user's word
     *
     * @param stdClass $user
     * @param array $words
     * @param int $lives
     * @return void
     */
    public function lostGameUpdateDB(stdClass $user, string $formattedBlacklist, int $lives, string $solution): void
    {
        DB::table('users_words')->updateOrInsert(
            ['user_id' => $user->id],
            ['lives' => $lives, 'blacklist' => $formattedBlacklist, 'frontend_word' => $solution]
        );
    }

    /**
     * Update lives and blacklist on bad guess
     *
     * @param stdClass $user
     * @param integer $lives
     * @param string $formattedBlacklist
     * @return void
     */
    public function badGuessUpdateDB(stdClass $user, int $lives, string $formattedBlacklist): void
    {
        DB::table('users_words')->updateOrInsert(
            ['user_id' => $user->id],
            ['lives' => $lives, 'blacklist' => $formattedBlacklist]
        );
    }
}
