<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Create new admin
    public function createAdmin(Request $request, $userId)
    {
        try {
            // Create admin
            Admin::create([
                'user_id' => $userId,
            ]);

            // Get admin
            $admin = Admin::where('user_id', $userId)->first();

            // Check if admin exists
            if ($admin) {
                // Return success response
                response()->json([
                    'status' => true,
                    'message' => 'Admin created successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Admin not created',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get admin
    public function getAdmin($userId = null)
    {
        try {
            // Get admin
            if ($userId) {
                // Get admin by user ID
                $admin = Admin::where('user_id', $userId)->first();
            } else {
                // Get admin from authenticated user
                $userId = auth()->user()->id;
                $admin = Admin::where('user_id', $userId)->first();
            }

            // Check if admin exists
            if ($admin) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'admin' => $admin,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Admin not found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Delete admin
    public function deleteAdmin($userId = null)
    {
        try {
            // Get admin
            if ($userId) {
                // Get admin by user ID
                $admin = Admin::where('user_id', $userId)->first();
            } else {
                // Get admin from authenticated user
                $userId = auth()->user()->id;
                $admin = Admin::where('user_id', $userId)->first();
            }

            // Check if admin exists
            if ($admin) {
                // Delete admin
                $admin->delete();

                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Admin deleted successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Admin not found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get all admins
    public function getAllAdmins()
    {
        try {
            // Get admins
            $admins = Admin::all();

            // Check if admins exist
            if ($admins) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'admins' => $admins,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'No admins found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
