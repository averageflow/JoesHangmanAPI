<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * API Routes
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/unauthorized', function () {
    return response()->json(['error' => 'Please login to access the API'], 403);
})->name('unauthorized');
Route::get('/unauthorized', function () {
    return response()->json(['error' => 'Please login to access the API'], 403);
});

Route::prefix('v1')->group(function () {

    //Routes without authentication
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');

    //Routes with authentication
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('get_word', 'Api\WordController@getRandomWord');
        Route::post('get_current_word', 'Api\WordController@getCurrentWord');
        Route::post('guess_letter', 'Api\GameController@respondToGuess');
        Route::post('insert_word', 'Api\WordController@insertNewWord');
        Route::post('get_hangman', 'Api\ImageController@getHangman');
        Route::post('get_score', 'Api\ScoreController@getScore');
        Route::post('set_score', 'Api\ScoreController@setScore');
        Route::post('getUser', 'Api\AuthController@getUser');
        Route::post('get_user_by_id', 'Api\AuthController@getUserByID');

    });
});
