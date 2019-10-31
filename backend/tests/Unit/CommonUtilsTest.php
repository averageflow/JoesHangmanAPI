<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserWordRepo;

class CommonUtilsTest extends TestCase
{
    /**
     * Get Auth token for API
     *
     * @return string
     */
    public function getToken(): string
    {
        $data = [
            'email' => 'support@testuser.com',
            'password' => '123456'
        ];

        $response = $this->json('POST', '/api/v1/login', $data);
        $response->assertStatus(200);
        $content = $response->decodeResponseJson();
        $myToken = $content["success"]["token"];
        return 'Bearer ' . $myToken;
    }

    /**
     * Get user Collection by email
     */
    public function testGetUser(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST','/api/v1/get_user_by_id', ["id"=>1]);
        //fwrite(STDERR, print_r($response->decodeResponseJson(), TRUE));
        $response->assertJsonStructure(['id', 'email','name','email_verified_at','password','remember_token', 'created_at', 'updated_at']);
    }

    /**
     * Get user word data for the game
     */
    public function testGetUserWordData(): void
    {
        $utils = new UserWordRepo();

        $this->assertEquals(DB::table('users_words')->where('user_id', '=', "1")->first(), $utils->getUserWordData('support@testuser.com'));
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
        $this->assertEquals(implode("",["_", "_", "_", "_", "A", "_", "A", "A", "_"]), $utils->replaceGuessedLetters($letters, $requestedLetter, $dashes));
    }
}
