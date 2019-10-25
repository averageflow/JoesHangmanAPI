<?php

namespace App\Providers;

use App\Repositories\UserScoresRepo;
use App\Repositories\Interfaces\UserScoresRepoInterface;
use App\Repositories\UserWordsRepo;
use App\Repositories\Interfaces\UserWordsRepoInterface;
use App\Repositories\WordsRepo;
use App\Repositories\Interfaces\UsersRepoInterface;
use App\Repositories\UsersRepo;
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
        $this->app->bind(
            UsersRepoInterface::class,
            UsersRepo::class
        );
        $this->app->bind(
            WordsRepoInterface::class,
            WordsRepo::class
        );

    }
}
