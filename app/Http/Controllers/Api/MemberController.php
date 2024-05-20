<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;


class MemberController extends Controller
{

    // Create new member
    public function createMember(Request $request, $userId)
    {
        try {
            // Validate request
            $request->validate([
                'home_club_address' => 'required',
                'fitness_level' => 'required',
                'workout_types' => 'required',
            ]);

            // Create member
            Member::create([
                'user_id' => $userId,
                'home_club_address' => $request->home_club_address,
                'fitness_level' => $request->fitness_level,
                'workout_types' => $request->workout_types,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get member
            $member = Member::where('user_id', $userId)->first();

            // Check if member exists
            if ($member) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Member created successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Member not created',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get member
    public function getMember($memberId = null)
    {
        try {
            // Get member
            if ($memberId) {
                // Get member by member ID
                $member = Member::where('id', $memberId)->first();
            } else {
                // Get member from authenticated user
                $member = Member::where('user_id', auth()->user()->id)->first();
            }
    
            // Check if member exists
            if ($member) {
                // Manually load user relationship
                $member->load('user');
    
                // Return success response with user information included
                return response()->json([
                    'status' => true,
                    'member' => $member,
                ]);
            }
    
            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Member not found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }    

    // Update member
    public function updateMember(Request $request, $memberId = null)
    {
        try {
            // Validate request
            $request->validate([
                'home_club_address' => 'required',
                'fitness_level' => 'required',
                'workout_types' => 'required',
            ]);

            // Get member
            if ($memberId) {
                // Get member by member ID
                $member = Member::where('id', $memberId)->first();
            } else {
                // Get member from authenticated user
                $member = Member::where('user_id', auth()->user()->id)->first();
            }

            // Check if member exists
            if ($member) {
                // Update member
                $member->update([
                    'home_club_address' => $request->home_club_address,
                    'fitness_level' => $request->fitness_level,
                    'workout_types' => $request->workout_types,
                    'updated_at' => now(),
                ]);

                // Eager load user information
                $member->load('user');

                // Return success response with user information included
                return response()->json([
                    'status' => true,
                    'message' => 'Member updated successfully',
                    'member' => $member,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Member not updated',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get all members
    public function getAllMembers()
    {
        try {
            // Get all members
            $members = Member::with('user')->get();

            // Check if members exist
            if ($members->isNotEmpty()) {
                // Return success response
                return response()->json([
                    "status" => true,
                    "members" => $members
                ]);
            }

            // Return error response
            return response()->json([
                "status" => false,
                "message" => "Members not found"
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
