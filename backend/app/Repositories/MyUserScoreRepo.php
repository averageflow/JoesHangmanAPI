<?php

namespace App\Repositories;

use App\Models\MyUser;
use App\Models\MyUserScore;
use App\Repositories\Interfaces\MyUserScoreRepoInterface;
use Illuminate\Http\JsonResponse;


class MyUserScoreRepo implements MyUserScoreRepoInterface
{
    protected $usersRepo;

    public function __construct()
    {
        $this->usersRepo = new MyUserRepo();
    }

    /**
     * Get wins and losses of user
     *
     * @param string $user
     * @return JsonResponse
     */
    public function getScore(string $user): JsonResponse
    {
        $user = $this->usersRepo->getUserByEmail($user);

        $score = MyUserScore::getUserScore(intval($user["id"]));
        if ($score) {
            return response()->json($score);
        }
        return response()->json(["error" => "Could not get score!"]);
    }

    /**
     * Set wins and losses of user
     *
     * @param string $outcome
     * @param string $user
     * @return JsonResponse
     */
    public function setScore(string $outcome, string $user): JsonResponse
    {
        $user = $this->usersRepo->getUserByEmail($user);

        if ($outcome == "won") {
            $this->increaseWins($user);
            return response()->json(['success' => true]);
        }
        if ($outcome == "lost") {
            $this->increaseLosses($user);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Could not set score!']);
    }

    /**
     * Increase user's wins
     *
     * @param MyUser $user
     * @return void
     */
    public function increaseWins(MyUser $user): void
    {
        $currentID = intval($user["id"]);
        $wins = MyUserScore::getWinsByID($currentID);
        MyUserScore::updateUserWins($currentID, $wins + 1);
    }

    /**
     * Increase user's losses
     *
     * @param MyUser $user
     * @return void
     */
    public function increaseLosses(MyUser $user): void
    {
        $currentID = intval($user["id"]);
        $losses = MyUserScore::getLossesByID($currentID);
        MyUserScore::updateUserLosses($currentID, $losses + 1);
    }
}
