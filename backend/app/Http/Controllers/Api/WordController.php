<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\WordsModel;
use Illuminate\Support\Facades\DB;

class WordController extends Controller
{
    public function getRandomWord()
    {
        $randomWord = WordsModel::select('word')->where('language','=',request('language'))->inRandomOrder()->first()->word;
        //Log::error($randomWord);
        $frontEndWord = "";
        for ($i = 0; $i < strlen($randomWord); $i++) {
            if ($randomWord[$i] == "-") {
                $frontEndWord .= "-";
            } else if ($randomWord[$i] == " ") {
                $frontEndWord .= " ";
            } else {
                $frontEndWord .= "_";
            }
        }

        if (request('newWord') == true && request('user') !== null) {
            $user = DB::table('users')->where('email', '=', request('user'))->first();
            DB::table('users_words')->updateOrInsert(
                ['user_id' => $user->id],
                ['word' => $randomWord, 'frontend_word' => $frontEndWord, 'lives' => 12, 'blacklist' => '']
            );
            return response()->json(['word' => $frontEndWord, 'lives' => 12]);
        }
        return response()->json(['error' => 'There was an error fetching a new word!']);
    }

    public function getCurrentWord()
    {
        if (request('newWord') == false && request('user') !== null) {
            $user = DB::table('users')->where('email', '=', request('user'))->first();
            $userWordData = DB::table('users_words')->where('user_id', '=', $user->id)->first();
            if($userWordData == null){
                return response()->json(['status'=>'No records available!']);
            }
            $currentWord = $userWordData->frontend_word;
            $lives = $userWordData->lives;
            $blacklist = $userWordData->blacklist;
            return response()->json(['word' => $currentWord, 'lives' => $lives, 'blacklist' => $blacklist]);
        }
    }
    public function insertNewWord()
    {
        if (request('word') != null && request('language') != null) {
            DB::table('words')->insert(['word' => request('word'), 'language' => request('language')]);
            return response()->json(['success' => true]);
        }
    }
}
