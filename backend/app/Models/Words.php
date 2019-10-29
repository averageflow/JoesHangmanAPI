<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model describing the words table in DB
 * Consists of word_id(id), word and language
 * @mixin Builder
 */
class Words extends Model
{
    protected $table = "words";

    public static function insertNewWord(string $word, string $language)
    {
        (new Words)->insert(['word' => $word, 'language' => $language]);
    }

    public static function retrieveRandomWord($userLang)
    {
        return (new Words)->select('word')
            ->where('language', '=', $userLang)
            ->inRandomOrder()->first()->word;

    }
}
