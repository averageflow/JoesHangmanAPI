<?php

namespace App\Repositories\Interfaces;


use App\Models\MyUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface MyUserRepoInterface
{
    public function getUserByEmail(string $email): MyUser;

    public function getUserByID(int $id): JsonResponse;
}
