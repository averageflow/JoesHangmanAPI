<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Responds to a number of lives and gives images according
 */
class ImageController extends Controller
{
    /**
     * Get Base64 image of corresponding hangman according to lives
     *
     * @return JsonResponse
     */
    public function getHangman($lives = null): JsonResponse
    {
        if ($lives) {
            $image = DB::table('hangman_images')->where('lives', '=', $lives)->first()->data;
            return response()->json(['hangman' => $image]);
        }
        $image = DB::table('hangman_images')->where('lives', '=', request('lives'))->first()->data;
        return response()->json(['hangman' => $image]);
    }
}
