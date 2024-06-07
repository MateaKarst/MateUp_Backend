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
                'workout_types' => 'required',
                'level_fitness' => 'required',
                'workout_types' => 'required',
            ]);

            // Create member
            Member::create([
                'user_id' => $userId,
                'home_club_address' => $request->home_club_address,
                'level_fitness' => $request->level_fitness,
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
                'level_fitness' => 'required',
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
                    'level_fitness' => $request->level_fitness,
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
    public function getAllMembers(Request $request)
    {
        try {
            // Get the filter values from the request
            $home_club_address = $request->input('home_club_address');
            $level_fitness = $request->input('level_fitness');
            $workout_types = $request->input('workout_types');

            // Create the query
            $query = Member::with('user');

            // Add the filter conditions if they are provided
            if ($home_club_address) {
                $home_club_address = trim($home_club_address);
                $query->where('home_club_address', $home_club_address);
            }

            if ($level_fitness) {
                $level_fitness = trim($level_fitness);
                $query->where('level_fitness', $level_fitness);
            }

            // Execute the query to get all members initially
            $members = $query->get();

            // Filter by workout types if provided
            if ($workout_types) {
                $workoutTypesArray = explode(',', $workout_types);
                $workoutTypesArray = array_map('trim', $workoutTypesArray); // Trim whitespace from each value

                $members = $members->filter(function ($member) use ($workoutTypesArray) {
                    $memberWorkoutTypes = explode(',', $member->workout_types);
                    $memberWorkoutTypes = array_map('trim', $memberWorkoutTypes);
                    foreach ($workoutTypesArray as $workoutType) {
                        if (in_array($workoutType, $memberWorkoutTypes)) {
                            return true;
                        }
                    }
                    return false;
                });
            }

            // Check if members exist
            if ($members->isNotEmpty()) {
                // Return success response
                return response()->json([
                    "status" => true,
                    "members" => $members->values() // Re-index the collection
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
