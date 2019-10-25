<?php

namespace App\Providers;

use App\Repositories\UserScoresRepo;
use App\Repositories\Interfaces\UserScoresRepoInterface;
use App\Repositories\UserWordsRepo;
use App\Repositories\Interfaces\UserWordsRepoInterface;
use App\Repositories\WordsRepo;
use App\Repositories\Interfaces\WordsRepoInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            UserScoresRepoInterface::class,
            UserScoresRepo::class
        );
        $this->app->bind(
            UserWordsRepoInterface::class,
            UserWordsRepo::class
        );

    }
}
