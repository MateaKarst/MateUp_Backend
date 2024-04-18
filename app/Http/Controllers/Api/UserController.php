<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'is_basic_fit' => 'required',
            'role' => 'required',
            'home_club_address' => 'required',
            'name' => 'required',
            'surname' => 'required',
            'phone' => 'required',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_basic_fit' => $request->is_basic_fit,
            'role' => $request->role,
            'home_club_address' => $request->home_club_address,
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'profile_image_url' => $request->profile_image_url,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully'
        ]);
    }

    // Login user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!empty($token)) {
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ]);
    }

    // User profile
    public function profile()
    {

        $user = auth()->user();

        return response()->json([
            'status' => true,
            'message' => 'User profile',
            'user' => $user
        ]);
    }

    // Refresh token
    public function refreshToken()
    {

        $newToken = JWTAuth::refresh(JWTAuth::getToken());

        return response()->json([
            'status' => true,
            'message' => 'New token refreshed successfully',
            'token' => $newToken
        ]);
    }

    // Logout user
    public function logout()
    {

        auth()->logout();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ]);
    }

    // Update user
    public function updateUser(Request $request)
    {
        $user = auth()->user();

        if ($user instanceof \App\Models\User) {
            $request->validate([
                'username' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'confirmed',
                'is_basic_fit' => 'required',
                'home_club_address' => 'required',
                'name' => 'required',
                'surname' => 'required',
                'phone' => 'required',
                'bio' => 'nullable',
                'profile_image_url' => 'nullable',
                'facebook' => 'nullable',
                'instagram' => 'nullable',
                'twitter' => 'nullable',
            ]);

            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => isset($request->password) ? Hash::make($request->password) : $user->password,
                'is_basic_fit' => $request->is_basic_fit,
                'home_club_address' => $request->home_club_address,
                'name' => $request->name,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'bio' => $request->bio,
                'profile_image_url' => $request->profile_image_url,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not found'
        ]);
    }

    // Delete user
    public function deleteUser()
    {
        $user = auth()->user();

        if ($user instanceof \App\Models\User) {
            // Delete associated member or trainer record
            if (!empty($user->member)) {
                $user->member->delete();
            }

            if (!empty($user->trainer)) {
                $user->trainer->delete();
            }

            // Now delete the user
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not found'
        ]);
    }
}
