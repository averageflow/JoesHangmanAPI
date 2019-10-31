<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MyUser;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Validator;

/**
 * Controls authentication mechanisms
 */
class AuthController extends Controller
{
    public $successStatus = 200;

    /**
     * Register user in application
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('AppName')->accessToken;
        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Log user in to application
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('AppName')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Get the user token
     *
     * @return JsonResponse
     */
    public function getUser(): JsonResponse
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    /**
     * Return user details when ID is provided
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getUserByID(string $id): JsonResponse
    {
        $userDetails = (array)MyUser::where('id', '=', $id)->first();
        return response()->json($userDetails);
    }
}
