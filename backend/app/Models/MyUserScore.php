<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model describing the user score table in DB
 * Consists of user_id, wins and losses
 * @mixin Builder
 */
class MyUserScore extends Model
{
    protected $table = "users_scores";

    /**
     *
     * @param int $userID
     * @return array
     */
    public static function getUserScore(int $userID): array
    {
        $score = (new MyUserScore)->where('id', '=', $userID)->first();
        return ["wins" => $score->wins, "losses" => $score->losses];

    }

    /**
     * @param int $userID
     * @return int
     */
    public static function getWinsByID(int $userID): int
    {
        $wins = (new MyUserScore)->where('id', '=', $userID)->first()->wins;
        return intval($wins);
    }

    /**
     * @param int $userID
     * @return int
     */
    public static function getLossesByID(int $userID): int
    {
        $losses = (new MyUserScore)->where('id', '=', $userID)->first()->losses;
        return intval($losses);
    }

    /**
     * @param int $userID
     * @param int $wins
     */
    public static function updateUserWins(int $userID, int $wins): void
    {
        (new MyUserScore)->updateOrInsert(['id' => $userID], ['wins' => $wins]);
    }

    /**
     * @param int $userID
     * @param int $losses
     */
    public static function updateUserLosses(int $userID, int $losses): void
    {
        (new MyUserScore)->updateOrInsert(['id' => $userID], ['losses' => $losses]);
    }

}
