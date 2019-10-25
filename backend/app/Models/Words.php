<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Model describing the words table in DB
 * Consists of word_id(id), word and language
 */
class Words extends Model
{
    protected $table = "words";
}