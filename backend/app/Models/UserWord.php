<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Model describing the users words table in DB
 * Consists of user_id, word, frontend_word, lives, blacklist and user language
 * @mixin Builder
 */
class UserWord extends Model
{
    protected $table = "users_words";

    /**
     * @param $userID
     * @return string
     */
    public static function getFrontendWord(int $userID): string
    {
        return (new UserWord)->select('frontend_word')
            ->where('user_id', '=', $userID)
            ->first()->frontend_word;
    }

    /**
     * @param int $userID
     * @return string
     */
    public static function getSolution(int $userID): string
    {
        return (new UserWord)->select('word')
            ->where('user_id', '=', $userID)
            ->first()->word;
    }

    /**
     * @param int $userID
     * @return int
     */
    public static function getLives(int $userID): int
    {
        $lives = (new UserWord)->select('lives')
            ->where('user_id', '=', $userID)
            ->first()->lives;

        return intval($lives);
    }

    /**
     * @param int $userID
     * @return string
     */
    public static function getBlacklist(int $userID):string
    {
        return (new UserWord)->select('blacklist')
            ->where('user_id', '=', $userID)
            ->first()->blacklist;
    }

    /**
     * @param int $userID
     * @return string
     */
    public static function getPreferredLanguage(int $userID):string
    {
        return (new UserWord)->select('prefered_language')
            ->where('user_id', '=', $userID)
            ->first()->prefered_language;
    }

    /**
     * @param int $id
     * @param array $words
     * @param int $lives
     */
    public static function renewUserWords(int $id, array $words, int $lives)
    {
        (new UserWord)->updateOrInsert(
            ['user_id' => $id],
            [
                'word' => $words["solution"],
                'frontend_word' => $words["enigma"],
                'lives' => $lives,
                'blacklist' => ''
            ]
        );
    }

    /**
     * Get user word data for the game
     *
     * @param string $userID
     * @return UserWord
     */
    public static function getUserWordData($userID)
    {
        return (new UserWord)->where('user_id', '=', $userID)->first();
    }
}

