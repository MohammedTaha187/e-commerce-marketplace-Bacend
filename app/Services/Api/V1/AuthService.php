<?php

namespace App\Services\Api\V1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return array
     */
    public function registerUser(array $data): array
    {
        // Check if user exists and is blocked
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser && $existingUser->is_blocked) {
            throw new Exception('This email has been blocked. Please contact support. Reason: ' . ($existingUser->block_reason ?? 'not specified'), 403);
        }

        $imagePath = $data['image']->store('user', 'public');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'image' => $imagePath,
        ]);

        $user->assignRole('customer'); // Default role, assuming 'customer' or 'user' based on previous context. Let's stick to 'customer' as per seeder.

        $token = Auth::guard('api')->login($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Login a user.
     *
     * @param array $credentials
     * @return array
     */
    public function loginUser(array $credentials): array
    {
        if (! $token = Auth::guard('api')->attempt($credentials)) {
            throw new Exception(__('lang.Invalid credentials'), 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::guard('api')->user();

        if ($user->is_blocked) {
            Auth::guard('api')->logout();
            throw new Exception('Your account has been blocked. Please contact support. Reason: ' . ($user->block_reason ?? 'not specified'), 403);
        }

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Update user profile.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
       

        if (isset($data['password']) && !$user->google_id) {
            if (!Hash::check($data['old_password'], $user->password)) {
                throw new Exception('Current password is incorrect', 422);
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (isset($data['phone'])) {
            $user->phone = $data['phone'];
        }

        if (isset($data['image'])) {
            $imagePath = $data['image']->store('user', 'public');
            $user->image = $imagePath;
        }

        $user->save();

        return $user;
    }

    /**
     * Logout user.
     *
     * @return void
     */
    public function logoutUser(): void
    {
        Auth::guard('api')->logout();
    }
}
