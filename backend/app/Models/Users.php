<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model describing the words table in DB
 * Consists of word_id(id), word and language
 * @mixin Builder
 */
class Users extends Model
{
    public $id;
    protected $table = "users";

    public static function getUserByEmail($email)
    {
        return (new Users)->where('email', '=', $email)->first();
    }

    public static function getUserByID($id)
    {
        return (array)(new Users)->where('id', '=', $id)->first();
    }
}
