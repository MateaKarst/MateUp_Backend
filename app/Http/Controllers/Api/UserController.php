<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class UserController extends Controller
{
    // Create and Register new user
    public function register(Request $request, $userId = null)
    {

        try {
            // Get admin user
            if ($userId) {
                // Get user by ID
                $admin = User::find($userId);
            } else {
                // Get authenticated admin user
                $admin = auth()->user();
            }

            // Check if admin user exists and has necessary permissions
            if (!$admin || $admin->role !== 'admin' || $admin->user_token === null) {
                return response()->json([
                    "status" => false,
                    "message" => "Unauthorized access"
                ], 401);
            }

            // Validate request
            $request->validate([
                "role" => "required",
                "email" => "required|email|unique:users",
                "password" => "required|confirmed",
                "name" => "required",
                "surname" => "required",
                "phone" => "required",
            ]);


            // Create user
            $registeringUser = User::create([
                "role" => $request->role,
                "username" => $request->name . $request->surname,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "name" => $request->name,
                "surname" => $request->surname,
                "phone" => $request->phone,
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            // Return success response
            return response()->json([
                "status" => true,
                "message" => "User created successfully",
                "user" => $admin,
                "new user" => $registeringUser
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
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
            // Validate request
            $credentials = $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);

            // Attempt to log in with session authentication
            if (Auth::attempt($credentials)) {
                // Generate a unique token
                $token = Str::random(60);

                // Get the authenticated user
                $user = User::find(Auth::id());

                // Store the token in the database
                $user->user_token = hash('sha256', $token);
                $user->save();


                // check if user is member or trainer
                if ($user->role === 'member') {
                    $user->load('member'); // Assuming 'member' is the relationship name
                } elseif ($user->role === 'trainer') {
                    $user->load('trainer'); // Assuming 'trainer' is the relationship name
                }

                // Return success response with token
                return response()->json([
                    "status" => true,
                    "message" => "User logged in successfully",
                    "userToken" => $user->user_token,
                    "userData" => $user,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Invalid credentials"
                ]);
            }
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Logout user
    public function logout(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // get the user token
            $userToken = $user->user_token;

            if (!$userToken) {
                return response()->json([
                    "status" => false,
                    "message" => "User is not logged in"
                ]);
            }

            // Clear the user_token
            $user->user_token = null;
            $user->save();

            // Return success response
            return response()->json([
                "status" => true,
                "message" => "User logged out successfully",
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Get user
    public function getUser($userId = null)
    {
        try {
            // Get user
            if ($userId) {
                // Get user by ID
                $user = User::find($userId);
            } else {
                // Get user from authenticated user
                $userId = auth()->user()->id;
                $user = User::find($userId);
            }

            // Check if user exists
            if ($user) {
                // Return success response
                return response()->json([
                    "status" => true,
                    "user" => $user
                ]);
            }

            // Return error response
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Update user
    public function updateUser(Request $request, $userId = null)
    {
        try {
            // Get user
            if ($userId) {
                // Get user by ID
                $user = User::find($userId);
            } else {
                // Get user from authenticated user
                $userId = auth()->user()->id;
                $user = User::find($userId);
            }

            // Check if user exists
            if ($user instanceof \App\Models\User) {
                // Validate request
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

                // Update user
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
                    "updated_at" => now(),
                ]);

                // Return success response
                return response()->json([
                    "status" => true,
                    "message" => "User updated successfully",
                    "user" => $user
                ]);
            }

            // Return error response
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Delete user
    public function deleteUser($userId = null)
    {
        try {
            // Get user
            if ($userId) {
                // Get user by ID
                $user = User::find($userId);
            } else {
                // Get user from authenticated user
                $userId = auth()->user()->id;
                $user = User::find($userId);
            }

            // Check if user exists
            if ($user instanceof \App\Models\User) {

                // Delete associated member
                if (!empty($user->member)) {
                    $user->member->delete();

                    // Delete associated activities
                    if (!empty($user->member->activities)) {
                        $user->member->activities()->delete();
                    }
                }

                // Delete associated trainer record
                if (!empty($user->trainer)) {
                    $user->trainer->delete();
                }

                // Delete associated admin record
                if (!empty($user->admin)) {
                    $user->admin->delete();
                }

                // Now delete the user
                $user->delete();

                // Return success response
                return response()->json([
                    "status" => true,
                    "message" => "User deleted successfully"
                ]);
            }

            // Return error response
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
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
            // Get all users
            $users = User::all();

            // Check if users exist
            if ($users->isNotEmpty()) {
                // Return success response
                return response()->json([
                    "status" => true,
                    "users" => $users
                ]);
            }

            // Return error response
            return response()->json([
                "status" => false,
                "message" => "Users not found"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                // Catch any exceptions and return error response
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
