<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model describing the words table in DB
 * Consists of word_id(id), word and language
 * @mixin Builder
 */
class MyUser extends Model
{
    public $id;
    protected $table = "users";

    /**
     * Retrieve user details by specified email
     * @param string $email
     * @return MyUser
     */
    public static function getUserByEmail(string $email): MyUser
    {
        return (new MyUser)->where('email', '=', $email)->first();
    }

    /**
     * Retrieve user details by specified ID
     * @param int $userID
     * @return array
     */
    public static function getUserByID(int $userID): array
    {
        return (array)(new MyUser)->where('id', '=', $userID)->first();
    }
}
