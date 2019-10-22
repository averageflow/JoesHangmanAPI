<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Api\GameController;


class GameControllerTest extends TestCase
{
    /**
     * Lost the game
     *
     * @return void
     */
    public function testLostGame(): void
    {
        $gameController = new GameController();
        $user = (object) ['id' => '1', 'email' => 'support@testuser.com'];
        $response = $gameController->gameEval(0, "PINDAKAAS", "_________", ['Z', 'X'], 'L', $user);
        $expected = ['victory' => false, 'lives' => 0, 'successGuessing' => true, 'currentWord' => "PINDAKAAS", 'blacklist' => " Z X"];
        $this->assertEquals($expected, $response);
    }

    /**
     * Won the game
     *
     * @return void
     */
    public function testWonGame(): void
    {
        $gameController = new GameController();
        $user = (object) ['id' => '1', 'email' => 'support@testuser.com'];
        $response = $gameController->gameEval(5, "PINDAKAAS", "_________", ['Z', 'X'], 'P', $user);
        $expected = ['victory' => true, 'lives' => 5, 'successGuessing' => true, 'currentWord' => "PINDAKAAS", 'blacklist' => " Z X"];
        $this->assertEquals($expected, $response);
    }

    /**
     * Bad guess
     *
     * @return void
     */
    public function testBadGuess(): void
    {
        $gameController = new GameController();
        $user = (object) ['id' => '1', 'email' => 'support@testuser.com'];
        $response = $gameController->gameEval(5, "PINDAKAAS", "_________", ['Z', 'X'], 'L', $user);
        $expected = ['lives' => 5, 'successGuessing' => false, 'currentWord' => "PINDAKAAS", 'blacklist' => " Z X"];
        $this->assertEquals($expected, $response);
    }
}
