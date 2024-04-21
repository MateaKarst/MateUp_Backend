<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    // Create and Register new user
    public function register(Request $request)
    {
        try {
            $request->validate([
                "role" => "required",
                "email" => "required|email|unique:users",
                "password" => "required|confirmed",
                "name" => "required",
                "surname" => "required",
                "phone" => "required",
            ]);

            User::create([
                "role" => $request->role,
                "username" => $request->name . $request->surname,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "name" => $request->name,
                "surname" => $request->surname,
                "phone" => $request->phone,
            ]);

            return response()->json([
                "status" => true,
                "message" => "User created successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Login user
    public function login(Request $request)
    {
        try {
            $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);

            $token = JWTAuth::attempt([
                "email" => $request->email,
                "password" => $request->password
            ]);

            if (!empty($token)) {
                return response()->json([
                    "status" => true,
                    "message" => "User logged in successfully",
                    "token" => $token
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "Invalid credentials"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Logout user
    public function logout()
    {
        try {
            auth()->logout();

            return response()->json([
                "status" => true,
                "message" => "User logged out successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Refresh token
    public function refreshToken()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                "status" => true,
                "message" => "New token refreshed successfully",
                "token" => $newToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Get user
    public function getUser()
    {
        try {
            $user = auth()->user();

            if ($user) {
                return response()->json([
                    "status" => true,
                    "user" => $user
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Update user
    public function updateUser(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user instanceof \App\Models\User) {
                $request->validate([
                    "username" => "required",
                    "email" => "required|email|unique:users,email," . $user->id,
                    "password" => "confirmed",
                    "name" => "required",
                    "surname" => "required",
                    "phone" => "required",
                    "bio" => "nullable",
                    "profile_image_url" => "nullable",
                    "facebook" => "nullable",
                    "instagram" => "nullable",
                    "twitter" => "nullable",
                ]);

                $user->update([
                    "username" => $request->username,
                    "email" => $request->email,
                    "password" => isset($request->password) ? Hash::make($request->password) : $user->password,
                    "name" => $request->name,
                    "surname" => $request->surname,
                    "phone" => $request->phone,
                    "bio" => $request->bio,
                    "profile_image_url" => $request->profile_image_url,
                    "facebook" => $request->facebook,
                    "instagram" => $request->instagram,
                    "twitter" => $request->twitter,
                ]);

                return response()->json([
                    "status" => true,
                    "message" => "User updated successfully",
                    "user" => $user
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Delete user
    public function deleteUser()
    {
        try {
            $user = auth()->user();

            if ($user instanceof \App\Models\User) {
                // Delete associated member or trainer record
                if (!empty($user->member)) {
                    $user->member->delete();
                }

                if (!empty($user->trainer)) {
                    $user->trainer->delete();
                }

                if (!empty($user->admin)) {
                    $user->admin->delete();
                }

                // Now delete the user
                $user->delete();

                return response()->json([
                    "status" => true,
                    "message" => "User deleted successfully"
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Get all users
    public function getAllUsers()
    {
        try {
            $users = User::all();

            if ($users) {
                return response()->json([
                    "status" => true,
                    "users" => $users
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "Users not found"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
