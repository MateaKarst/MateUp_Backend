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
            Admin::create([
                'user_id' => $userId,
            ]);

            $admin = Admin::where('user_id', $userId)->first();

            if ($admin) {
                response()->json([
                    'status' => true,
                    'message' => 'Admin created successfully',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Admin not created',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get admin
    public function getAdmin($userId)
    {
        try {
            $admin = Admin::where('user_id', $userId)->first();

            if ($admin) {
                return response()->json([
                    'status' => true,
                    'admin' => $admin,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Admin not found',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Delete admin
    public function deleteAdmin()
    {
        try {
            $admin = Admin::where('user_id', auth()->user()->id)->first();

            if ($admin) {
                $admin->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Admin deleted successfully',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Admin not found',
            ]);
        } catch (\Exception $e) {
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
            $admins = Admin::all();

            if ($admins) {
                return response()->json([
                    'status' => true,
                    'admins' => $admins,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'No admins found',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
