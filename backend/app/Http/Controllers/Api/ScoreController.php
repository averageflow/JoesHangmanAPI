<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MyUserScoreRepoInterface;
use Illuminate\Http\JsonResponse;

/**
 * Controls the get and set methods of the score
 */
class ScoreController extends Controller
{
    private $userScoresRepo;

    public function __construct(MyUserScoreRepoInterface $userScoresRepo)
    {
        $this->userScoresRepo = $userScoresRepo;
    }

    /**
     * Get wins and losses of user
     *
     * @return JsonResponse
     */
    public function getScore(): JsonResponse
    {
        if (request('user') != null) {
            return $this->userScoresRepo->getScore(request('user'));
        }
        return response()->json(["error" => "Could not get score!"]);
    }


    /**
     * Set wins and losses of user
     *
     * @return JsonResponse
     */
    public function setScore(): JsonResponse
    {
        if (request('outcome') && request('user')) {
            return $this->userScoresRepo->setScore(request('outcome'), request('user'));
        }
        return response()->json(['error' => 'Could not set score!']);
    }
}
