<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * User login attempt.
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $request->persist();

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => $user,
        ], 200);
    }

    /**
     * Get the current authenticated user.
     *
     * @return JsonResponse
     */
    public function getCurrentAuthenticatedUser(Request $request): JsonResponse
    {
        return response()->json($request->user(), 200);
    }

    /**
     * Logout the user via provided access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token == null) return response()->json([
            'message' => 'No token found.'
        ], 204);

        return response()->json(
            $token->delete()
        , 204);
    }

    /**
     * Logout the user from all devices.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function logoutAllDevices(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->tokens()->delete()
        , 204);
    }
}
