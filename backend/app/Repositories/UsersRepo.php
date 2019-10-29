<?php

namespace App\Repositories;

use App\Models\Users;
use App\Repositories\Interfaces\UsersRepoInterface;
use Illuminate\Http\JsonResponse;


class UsersRepo implements UsersRepoInterface
{
    /**
     * Get user Collection by email
     *
     * @param string $email
     * @return Users
     */
    public function getUserByEmail(string $email): Users
    {
        return Users::getUserByEmail($email);
    }

    /**
     * Return user details when ID is provided
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getUserByID(string $id): JsonResponse
    {
        $userDetails = Users::getUserByID($id);
        return response()->json($userDetails);
    }
}
