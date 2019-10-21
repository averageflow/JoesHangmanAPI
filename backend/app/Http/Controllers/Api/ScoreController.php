<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function getScore():JsonResponse
    {
        $user = DB::table('users')->where('email', '=', request('user'))->first();

        $score = DB::table('users_scores')->where('id', '=', $user->id)->first();
        if ($score) {
            return response()->json(["wins" => $score->wins, "losses" => $score->losses]);
        }
        return response()->json(["error" => "Could not get score!"]);
    }
    public function setScore():JsonResponse
    {
        $outcome = request('outcome');
        $user = DB::table('users')->where('email', '=', request('user'))->first();

        if ($outcome == "won") {
            $wins = DB::table('users_scores')->where('id', '=', $user->id)->first()->wins;
            DB::table('users_scores')->updateOrInsert(['id' => $user->id], ['wins' => $wins + 1]);
            return response()->json(['success' => true]);
        }
        if ($outcome == "lost") {
            $losses = DB::table('users_scores')->where('id', '=', $user->id)->first()->losses;
            DB::table('users_scores')->updateOrInsert(['id' => $user->id], ['losses' => $losses + 1]);
            return response()->json(['success' => true]);
        }


        return response()->json(['error' => 'Could not set score!']);
    }
}
