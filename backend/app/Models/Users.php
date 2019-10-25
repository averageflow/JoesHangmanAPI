<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model describing the words table in DB
 * Consists of word_id(id), word and language
 */
class Users extends Model
{
    protected $table = "users";
}
