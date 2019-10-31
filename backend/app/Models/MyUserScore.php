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
     * @param int $userID
     * @return array
     */
    public static function getUserScore(int $userID): array
    {
        $score = (new MyUserScore)->where('id', '=', $userID)->first();
        return ["wins" => $score->wins, "losses" => $score->losses];

    }

    /**
     * @param int $id
     * @return MyUserScore
     */
    public static function getWinsByID(int $id): MyUserScore
    {
        return (new MyUserScore)->where('id', '=', $id)->first()->wins;
    }

    /**
     * @param int $id
     * @return MyUserScore
     */
    public static function getLossesByID(int $id): MyUserScore
    {
        return (new MyUserScore)->where('id', '=', $id)->first()->losses;
    }

    /**
     * @param int $id
     * @param int $wins
     */
    public static function updateUserWins(int $id, int $wins): void
    {
        (new MyUserScore)->updateOrInsert(['id' => $id], ['wins' => $wins]);
    }

    /**
     * @param int $id
     * @param int $losses
     */
    public static function updateUserLosses(int $id, int $losses): void
    {
        (new MyUserScore)->updateOrInsert(['id' => $id], ['losses' => $losses]);
    }

}
