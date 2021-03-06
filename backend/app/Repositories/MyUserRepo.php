<?php

namespace App\Repositories;

use App\Models\MyUser;
use App\Repositories\Interfaces\MyUserRepoInterface;
use Illuminate\Http\JsonResponse;


class MyUserRepo implements MyUserRepoInterface
{
    /**
     * Get user Collection by email
     *
     * @param string $email
     * @return MyUser
     */
    public function getUserByEmail(string $email): MyUser
    {
        return MyUser::getUserByEmail($email);
    }

    /**
     * Return user details when ID is provided
     *
     * @param int $userID
     * @return JsonResponse
     */
    public function getUserByID(int $userID): JsonResponse
    {
        $userDetails = MyUser::getUserByID($userID);
        return response()->json($userDetails);
    }
}
