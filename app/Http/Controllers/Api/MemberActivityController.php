<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Member;
use App\Models\User;

class ActivityController extends Controller
{
    // Create new member activity
    public function createActivity(Request $request, $memberId = null)
    {
        // Get member
        if ($memberId) {
            // Get member by user ID
            $member = Member::where('user_id', $memberId)->first();
            $user = User::where('id', $member->user_id)->first();
        } else {
            // Get member from authenticated user
            $memberId = auth()->user()->id;
            $member = Member::where('user_id', $memberId)->first();
            $user = User::where('id', $member->user_id)->first();
        }

        try {
            // Validate request
            $request->validate([
                'workout_type' => 'required',
                'location' => 'required',
                'date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'description' => 'required',
                'repeats' => 'nullable',
                'recurrence' => 'nullable',
                'reminder_date' => 'nullable|date',
                'reminder_time' => 'nullable',
            ]);

            // Create member activity
            Activity::create([
                'user_id' => $user->id,
                'member_id' => $member->id,
                'workout_type' => $request->workout_type,
                'location' => $request->location,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'description' => $request->description,
                'repeats' => $request->repeats,
                'recurrence' => $request->recurrence,
                'reminder_date' => $request->reminder_date,
                'reminder_time' => $request->reminder_time,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get activity
            $activity = Activity::where('user_id', auth()->user()->id)->where('member_id', $memberId)->first();

            // Check if activity exists
            if ($activity) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Activity created successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Activity not created',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get member activity
    public function getActivity($activityId, $memberId = null)
    {
        try {
            // Get member
            if ($memberId) {
                // Get member by user ID
                $member = Member::where('user_id', $memberId)->first();
            } else {
                // Get member from authenticated user
                $memberId = auth()->user()->id;
                $member = Member::where('user_id', $memberId)->first();
            }

            // Check if member exists
            if ($member) {
                // Get activity
                $activity = Activity::where('user_id', auth()->user()->id)->where('member_id', $memberId)->where('id', $activityId)->first();

                // Check if activity exists
                if ($activity) {
                    // Return success response
                    return response()->json([
                        "status" => true,
                        "activity" => $activity
                    ]);
                }

                // Return error response
                return response()->json([
                    "status" => false,
                    "message" => "Activity not found"
                ]);
            }

            // Return error response
            return response()->json([
                "status" => false,
                "message" => "Member not found"
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Update member activity
    public function updateActivity(Request $request, $activityId, $memberId = null)
    {
        // find the user and check for the activity
        try {
            // Get member
            if ($memberId) {
                // Get member by user ID
                $member = Member::where('user_id', $memberId)->first();
            } else {
                // Get member from authenticated user
                $memberId = auth()->user()->id;
                $member = Member::where('user_id', $memberId)->first();
            }

            // Check if member exists
            if ($member) {
                // Get activity
                $activity = Activity::where('user_id', auth()->user()->id)->where('member_id', $memberId)->where('id', $activityId)->first();

                // Check if activity exists
                if ($activity) {
                    // Update activity
                    $activity->update([
                        'title' => $request->title,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'description' => $request->description,
                        'repeats' => $request->repeats,
                        'recurrence' => $request->recurrence,
                        'reminder_date' => $request->reminder_date,
                        'reminder_time' => $request->reminder_time,
                        'updated_at' => now(),
                    ]);

                    // Return success response
                    return response()->json([
                        'status' => true,
                        'message' => 'Activity updated successfully',
                    ]);
                }

                // Return error response
                return response()->json([
                    'status' => false,
                    'message' => 'Activity not updated',
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
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Delete member activity
    public function deleteActivity($activityId, $memberId = null)
    {
        try {
            // Get member
            if ($memberId) {
                // Get member by user ID
                $member = Member::where('user_id', $memberId)->first();
            } else {
                // Get member from authenticated user
                $memberId = auth()->user()->id;
                $member = Member::where('user_id', $memberId)->first();
            }

            // Check if member exists
            if ($member) {
                // Get activity
                $activity = Activity::where('user_id', auth()->user()->id)->where('member_id', $memberId)->where('id', $activityId)->first();

                // Check if activity exists
                if ($activity) {
                    // Delete activity
                    $activity->delete();

                    // Return success response
                    return response()->json([
                        'status' => true,
                        'message' => 'Activity deleted successfully',
                    ]);
                }

                // Return error response
                return response()->json([
                    'status' => false,
                    'message' => 'Activity not deleted',
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
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Get all member activities
    public function getAllActivities($memberId = null)
    {
        try {
            // Get member
            if ($memberId) {
                // Get member by user ID
                $member = Member::where('user_id', $memberId)->first();
            } else {
                // Get member from authenticated user
                $memberId = auth()->user()->id;
                $member = Member::where('user_id', $memberId)->first();
            }

            // Check if member exists
            if ($member) {
                // Get activities
                $activities = Activity::where('user_id', auth()->user()->id)->where('member_id', $memberId)->get();

                // Return success response
                return response()->json([
                    'status' => true,
                    'activities' => $activities,
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
                'message' => $e->getMessage(),
            ]);
        }
    }
}
