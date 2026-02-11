<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UpdateProfileRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\Api\V1\AuthService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => __('lang.Users retrieved successfully'),
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'status' => 'success',
            'message' => __('lang.User retrieved successfully'),
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
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

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function block(User $user)
    {
        $user->update(['is_blocked' => true]);

        return response()->json([
            'status' => 'success',
            'message' => __('lang.User blocked successfully'),
        ]);
    }

    public function unblock(User $user)
    {
        $user->update(['is_blocked' => false]);

        return response()->json([
            'status' => 'success',
            'message' => __('lang.User unblocked successfully'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('lang.User deleted successfully'),
        ]);
    }
}
