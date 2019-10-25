<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Repositories\Interfaces\UserWordsRepoInterface;
use App\Repositories\Interfaces\WordsRepoInterface;

class WordController extends Controller
{
    private $userWordsRepo;
    private $wordsRepo;

    public function __construct(UserWordsRepoInterface $userWordsRepo, WordsRepoInterface $wordsRepo)
    {
        $this->userWordsRepo = $userWordsRepo;
        $this->wordsRepo = $wordsRepo;
    }


    public function getCurrentWord(): JsonResponse
    {
        if (request('newWord') == false && request('user')) {
            return $this->userWordsRepo->getCurrentWord(request('user'));
        }
        return response()->json(['error' => "There was an error fetching user's word!"]);
    }

    public function insertNewWord(): JsonResponse
    {
        if (request('word') && request('language')) {
            return $this->wordsRepo->insertNewWord(request('word'), request('language'));
        }
        return response()->json(['error' => 'There was an error inserting a new word!']);
    }

    public function getRandomWord(): JsonResponse
    {
        if (request('newWord') == true && request('user')) {
            return $this->userWordsRepo->getRandomWord(request('user'));
        }
        return response()->json(['error' => 'There was an error fetching a new word!']);
    }
}
