<?php

namespace App\Repositories;

use App\Models\UserScores;
use App\Models\Users;
use App\Repositories\Interfaces\UserScoresRepoInterface;
use Illuminate\Http\JsonResponse;


class UserScoresRepo implements UserScoresRepoInterface
{
    protected $usersRepo;

    public function __construct()
    {
        $this->usersRepo = new UsersRepo();
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

        $score = UserScores::where('id', '=', $user->id)->first();
        if ($score) {
            return response()->json(["wins" => $score->wins, "losses" => $score->losses]);
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
     * @param Users $user
     * @return void
     */
    public function increaseWins(Users $user): void
    {
        $wins = UserScores::where('id', '=', $user->id)->first()->wins;
        UserScores::updateOrInsert(['id' => $user->id], ['wins' => $wins + 1]);
    }

    /**
     * Increase user's losses
     *
     * @param stdClass $user
     * @return void
     */
    public function increaseLosses(Users $user): void
    {
        $losses = UserScores::where('id', '=', $user->id)->first()->losses;
        UserScores::updateOrInsert(['id' => $user->id], ['losses' => $losses + 1]);
    }
}
