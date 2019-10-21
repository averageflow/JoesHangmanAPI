<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    /**
     * Get Base64 image of corresponding hangman according to lives
     *
     * @return JsonResponse
     */
    public function getHangman():JsonResponse
    {
        $image = DB::table('hangman_images')->where('lives', '=', request('lives'))->first()->data;
        return response()->json(['hangman' => $image]);
    }
}
