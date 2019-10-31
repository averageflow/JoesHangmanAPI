<?php

namespace App\Providers;

use App\Repositories\GameRepo;
use App\Repositories\Interfaces\GameRepoInterface;
use App\Repositories\MyUserScoreRepo;
use App\Repositories\Interfaces\MyUserScoreRepoInterface;
use App\Repositories\UserWordRepo;
use App\Repositories\Interfaces\UserWordRepoInterface;
use App\Repositories\WordRepo;
use App\Repositories\Interfaces\MyUserRepoInterface;
use App\Repositories\MyUserRepo;
use App\Repositories\Interfaces\WordRepoInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            MyUserScoreRepoInterface::class,
            MyUserScoreRepo::class
        );
        $this->app->bind(
            UserWordRepoInterface::class,
            UserWordRepo::class
        );
        $this->app->bind(
            MyUserRepoInterface::class,
            MyUserRepo::class
        );
        $this->app->bind(
            WordRepoInterface::class,
            WordRepo::class
        );
        $this->app->bind(
            GameRepoInterface::class,
            GameRepo::class
        );

    }
}
