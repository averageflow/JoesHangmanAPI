<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\CommonUtils;
use stdClass;

/**
 * Controls the get and set methods of the score
 */
class ScoreController extends Controller
{
    protected $commonUtils;

    /**
     * Get wins and losses of user
     *
     * @return JsonResponse
     */
    public function getScore(): JsonResponse
    {
        $user = DB::table('users')->where('email', '=', request('user'))->first();

        $score = DB::table('users_scores')->where('id', '=', $user->id)->first();
        if ($score) {
            return response()->json(["wins" => $score->wins, "losses" => $score->losses]);
        }
        return response()->json(["error" => "Could not get score!"]);
    }

    /**
     * Increase user's wins
     *
     * @param stdClass $user
     * @return void
     */
    public function increaseWins(stdClass $user):void
    {
        $wins = DB::table('users_scores')->where('id', '=', $user->id)->first()->wins;
        DB::table('users_scores')->updateOrInsert(['id' => $user->id], ['wins' => $wins + 1]);
    }

    /**
     * Increase user's losses
     *
     * @param stdClass $user
     * @return void
     */
    public function increaseLosses(stdClass $user):void
    {
        $losses = DB::table('users_scores')->where('id', '=', $user->id)->first()->losses;
        DB::table('users_scores')->updateOrInsert(['id' => $user->id], ['losses' => $losses + 1]);
    }

    /**
     * Set wins and losses of user
     *
     * @return JsonResponse
     */
    public function setScore(): JsonResponse
    {
        $outcome = request('outcome');
        $user = $this->commonUtils->getUserByEmail(request('user'));

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

    public function __construct()
    {
        $this->commonUtils = new CommonUtils();
    }
}
