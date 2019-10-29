<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GameRepo;
use Illuminate\Http\JsonResponse;

/**
 * Controls the game flow based on guesses
 */
class GameController extends Controller
{
    private $gameRepo;

    public function __construct()
    {
        $this->gameRepo = new GameRepo();
    }


    /**
     * Respond to a letter guess
     *
     * @return JsonResponse
     */
    public function respondToGuess(): JsonResponse
    {
        $requestedLetter = trim(strtoupper(request('letter')));

        if ($requestedLetter && request('user')) {

            (new GameRepo)->respondToGuess(request('letter'), request('user'));
        }
        return response()->json(["error" => "Your guess was empty!"]);
    }


}
