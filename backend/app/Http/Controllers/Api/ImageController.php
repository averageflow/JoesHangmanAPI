<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{

    public function getHangman()
    {
        $image = DB::table('hangman_images')->where('lives', '=', request('lives'))->first()->data;
        return response()->json(['hangman' => $image]);
    }
}
