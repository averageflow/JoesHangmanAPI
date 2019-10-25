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
     * @return stdClass
     */
    public function getUserByEmail(string $email): Users
    {
        return Users::where('email', '=', $email)->first();
    }

    /**
     * Return user details when ID is provided
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getUserByID(string $id): JsonResponse
    {
        $userDetails = (array) Users::where('id', '=', $id)->first();
        return response()->json($userDetails);
    }
}
