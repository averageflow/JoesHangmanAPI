<?php

namespace App\Repositories;

use App\Models\MyUser;
use App\Repositories\Interfaces\MyUserRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class MyUserRepo implements MyUserRepoInterface
{
    /**
     * Get user Collection by email
     *
     * @param string $email
     * @return Collection
     */
    public function getUserByEmail(string $email): MyUser
    {
        return MyUser::getUserByEmail($email);
    }

    /**
     * Return user details when ID is provided
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getUserByID(int $id): JsonResponse
    {
        $userDetails = MyUser::getUserByID($id);
        return response()->json($userDetails);
    }
}
