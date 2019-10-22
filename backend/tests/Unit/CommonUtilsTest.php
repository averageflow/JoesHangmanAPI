<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Api\CommonUtils;
use Illuminate\Support\Facades\DB;


class CommonUtilsTest extends TestCase
{
    /**
     * Get user Collection by email
     */
    public function testGetUserByEmail(): void
    {
        $user = (object) ['id' => '1', 'email' => 'support@testuser.com'];
        $utils = new CommonUtils();
        $this->assertEquals($user, $utils->getUserByEmail('support@testuser.com'));
    }

    /**
     * Get user word data for the game
     */
    public function testGetUserWordData(): void
    {
        $utils = new CommonUtils();
        $user = (object) ['id' => '1', 'email' => 'support@testuser.com'];
        $this->assertEquals(DB::table('users_words')->where('user_id', '=', "1")->first(), $utils->getUserWordData($user));
    }

    /**
     * Replace dashes by letters in word
     */
    public function testReplaceGuessedLetters(): void
    {
        $utils = new CommonUtils();
        $letters = ["P", "I", "N", "D", "A", "K", "A", "A", "S"];
        $dashes = ["_", "_", "_", "_", "_", "_", "_", "_", "_"];
        $requestedLetter = "A";
        $this->assertEquals(["_", "_", "_", "_", "A", "_", "A", "A", "_"], $utils->replaceGuessedLetters($letters, $requestedLetter, $dashes));
    }
}
