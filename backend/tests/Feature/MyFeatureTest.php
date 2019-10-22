<?php

namespace Tests\Feature;

use Tests\TestCase;

class MyFeatureTest extends TestCase
{
    //fwrite(STDERR, print_r($response->decodeResponseJson(), TRUE));
    //fwrite(STDERR, print_r($response->decodeResponseJson(), TRUE));

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRequestAPI(): void
    {
        $response = $this->get('/');
        $response->assertSee('You are not allowed to access the Hangman API without authorization and without using the frontend application.');
        $response->assertStatus(403);
    }

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
     * Test the login mechanism
     *
     * @return void
     */
    public function testLogin(): void
    {
        $data = [
            'email' => 'support@testuser.com',
            'password' => '123456'
        ];

        $response = $this->json('POST', '/api/v1/login', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(["success" => ["token"]]);
    }

    /**
     * Get a random word in chosen lang from DB
     *
     * @return void
     */
    public function testGetRandomWord(): void
    {
        $data = ["newWord" => true, "user" => "support@testuser.com", "language" => "en"];
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST', '/api/v1/get_word', $data);
        $response->assertJsonStructure(['word', 'lives']);
    }

    /**
     * Get current enigma word and vars for game
     *
     * @return void
     */
    public function testGetCurrentWord(): void
    {
        $data = ["newWord" => false, "user" => "support@testuser.com"];
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST', '/api/v1/get_current_word', $data);
        $response->assertJsonStructure(['word', 'lives', 'blacklist']);
    }

    /**
     * Get corresponding hangman image
     *
     * @return void
     */
    public function testGetHangmanImage(): void
    {
        $data = ["lives" => 5];
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST', '/api/v1/get_hangman', $data);
        $response->assertJsonStructure(['hangman']);
    }

    /**
     * Get response from guess
     *
     * @return void
     */
    public function testGuessLetter(): void
    {
        $data = ["letter" => "A", "user" => "support@testuser.com"];
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST', '/api/v1/guess_letter', $data);
        $response->assertJsonStructure(['lives', 'currentWord', 'blacklist']);
    }

    /**
     * Get user score
     *
     * @return void
     */
    public function testGetScore(): void
    {
        $data = ["user" => "support@testuser.com"];
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST', '/api/v1/get_score', $data);
        $response->assertJsonStructure(['wins', 'losses']);
    }

    /**
     * Set user score
     *
     * @return void
     */
    public function testSetScore(): void
    {
        $data = ["outcome" => "won", "user" => "support@testuser.com"];
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST', '/api/v1/set_score', $data);
        $response->assertJsonStructure(['success']);
    }

    /**
     * Insert word into DB
     *
     * @return void
     */
    public function testInsertWord(): void
    {
        $data = ["word" => "PHPFOREVER", "language" => "en"];
        $response = $this->withHeaders(['Authorization' => $this->getToken()])->json('POST', '/api/v1/insert_word', $data);
        $response->assertJsonStructure(['success']);
    }
}
