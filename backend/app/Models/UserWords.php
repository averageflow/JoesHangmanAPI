<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model describing the users words table in DB
 * Consists of user_id, word, frontend_word, lives, blacklist and user language
 * @mixin Builder
 */
class UserWords extends Model
{
    protected $table = "users_words";

    /**
     * @param $userID
     * @return UserWords
     */
    public static function getFrontendWord($userID)
    {
        return (new UserWords)->select('frontend_word')
            ->where('user_id', '=', $userID)
            ->first()->frontend_word;
    }

    public static function getSolution($userID)
    {
        return (new UserWords)->select('word')
            ->where('user_id', '=', $userID)
            ->first()->word;
    }

    /**
     * @param $userID
     * @return UserWords
     */
    public static function getLives($userID)
    {
        $lives = (new UserWords)->select('lives')
            ->where('user_id', '=', $userID)
            ->first()->lives;

        return intval($lives);
    }

    /**
     * @param $userID
     * @return UserWords
     */
    public static function getBlacklist($userID)
    {
        return (new UserWords)->select('blacklist')
            ->where('user_id', '=', $userID)
            ->first()->blacklist;
    }

    /**
     * @param $userID
     * @return UserWords
     */
    public static function getPreferredLanguage($userID)
    {
        return (new UserWords)->select('prefered_language')
            ->where('user_id', '=', $userID)
            ->first()->prefered_language;
    }

    /**
     * @param string $id
     * @param array $words
     * @param int $lives
     */
    public static function renewUserWords(string $id, array $words, int $lives)
    {
        (new UserWords)->updateOrInsert(
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
     * @return UserWords
     */
    public static function getUserWordData($userID)
    {
        return (new UserWords)->where('user_id', '=', $userID)->first();
    }
}

