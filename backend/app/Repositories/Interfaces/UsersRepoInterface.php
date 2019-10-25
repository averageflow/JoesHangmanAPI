<?php

namespace App\Repositories\Interfaces;


interface UsersRepoInterface
{
    public function getUserByEmail(string $email);

    public function getUserByID(string $id);
}
