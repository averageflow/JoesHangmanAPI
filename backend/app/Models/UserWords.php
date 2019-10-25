<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Model describing the users words table in DB
 * Consists of user_id, word, frontend_word, lives, blacklist and user language
 */
class UserWords extends Model
{
    protected $table = "users_words";
}
