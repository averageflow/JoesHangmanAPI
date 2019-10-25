<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model describing the user score table in DB
 * Consists of user_id, wins and losses
 */
class UserScores extends Model
{
    protected $table = "users_scores";
}
