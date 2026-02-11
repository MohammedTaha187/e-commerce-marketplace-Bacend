<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Requests\Api\V1\Auth\UpdateProfileRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Api\V1\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $result = $this->authService->registerUser($data);

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->loginUser($request->validated());

            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 401);
        }
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = $request->user();

            $updatedUser = $this->authService->updateUser($user, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => __('lang.User profile updated successfully'),
                'user' => new UserResource($updatedUser),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logoutUser();

        return response()->json([
            'message' => __('lang.User logged out successfully'),
        ], 200);
    }
}
