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
                'created_at' => now(),
                'updated_at' => now(),
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
    public function getAdmin($adminId = null)
    {
        try {
            // Get admin
            if ($adminId) {
                // Get admin by user ID
                $admin = Admin::where('id', $adminId)->first();               
            } else {
                // Get admin from authenticated user
                $admin = Admin::where('user_id', auth()->user()->id)->first();
            }
    
            // Check if admin exists
            if ($admin) {
                // Manually load user relationship
                $admin->load('user');
    
                // Return success response with user information included
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

    // Get all admins
    public function getAllAdmins()
    {
        try {
            // Get admins with user information eager loaded
            $admins = Admin::with('user')->get();
    
            // Check if admins exist
            if ($admins->isNotEmpty()) {
                // Return success response with user information included
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
