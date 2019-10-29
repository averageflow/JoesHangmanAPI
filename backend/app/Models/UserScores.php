<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model describing the user score table in DB
 * Consists of user_id, wins and losses
 * @mixin Builder
 */
class UserScores extends Model
{
    protected $table = "users_scores";

    /**
     * @param $userID
     * @return array
     */
    public static function getUserScore($userID)
    {
        $score = (new UserScores)->where('id', '=', $userID)->first();
        return ["wins" => $score->wins, "losses" => $score->losses];

    }

    public static function getWinsByID($id)
    {
        return (new UserScores)->where('id', '=', $id)->first()->wins;
    }

    public static function getLossesByID($id)
    {
        return (new UserScores)->where('id', '=', $id)->first()->losses;
    }

    public static function updateUserWins($id, int $wins)
    {
        (new UserScores)->updateOrInsert(['id' => $id], ['wins' => $wins]);
    }

    public static function updateUserLosses($id, int $losses)
    {
        (new UserScores)->updateOrInsert(['id' => $id], ['losses' => $losses]);
    }

}
