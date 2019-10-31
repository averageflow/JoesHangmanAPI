<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model describing the words table in DB
 * Consists of word_id(id), word and language
 * @mixin Builder
 */
class Word extends Model
{
    protected $table = "words";

    /**
     * Insert new word in table with user language
     * @param string $word
     * @param string $language
     * @return void
     */
    public static function insertNewWord(string $word, string $language): void
    {
        (new Word)->insert(['word' => $word, 'language' => $language]);
    }

    /**
     * Retrieve a random word according to user language
     * @param string $userLang
     * @return Word
     */
    public static function retrieveRandomWord(string $userLang): string
    {
        return (new Word)->select('word')
            ->where('language', '=', $userLang)
            ->inRandomOrder()->first()->word;

    }
}
