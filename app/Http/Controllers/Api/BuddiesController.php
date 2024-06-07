<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buddies;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class BuddiesController extends Controller
{

    // Add buddy
    public function addBuddy(Request $request, $buddyId)
    {
        try {
            // Get authenticated user
            $user = auth()->user();

            // Check if user exists
            if (!$user) {
                return response()->json([
                    "status" => false,
                    "message" => "User not found"
                ], 404);
            }

            // Get the user token
            $userToken = $user->user_token;

            // Get the user id
            $userId = $user->id;

            if (!$userToken) {
                return response()->json([
                    "status" => false,
                    "message" => "User is not logged in"
                ]);
            }

            // Get buddy
            $buddy = User::find($buddyId);
            if (!$buddy) {
                return response()->json(['message' => 'Buddy not found.'], 404);
            }

            if ($buddyId === $userId) {
                return response()->json(['message' => 'You cannot add yourself as a buddy.'], 400);
            }

            // Check if the buddy relationship already exists
            $existingBuddy = Buddies::where('user_id', $userId)->where('buddy_id', $buddyId)->first();

            // Return an error if the buddy relationship already exists
            if ($existingBuddy) {
                return response()->json(['message' => 'This buddy relationship already exists.'], 409);
            }

            // Create a new buddy relationship
            $buddy = Buddies::create([
                'user_id' => $userId,
                'buddy_id' => $buddyId,
                'status' => 'pending', // Default status
            ]);

            return response()->json(['message' => 'Buddy request sent successfully.', 'buddy' => $buddy], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    // Accept Buddy
    public function acceptBuddy(Request $request, $buddyId)
    {
        try {
            // Get authenticated user
            $user = auth()->user();

            // Check if user exists
            if (!$user) {
                return response()->json([
                    "status" => false,
                    "message" => "User not found"
                ], 404);
            }

            // Get the user id
            $userId = $user->id;

            if ($userId === $buddyId) {
                return response()->json(['message' => 'You cannot accept yourself as a buddy.'], 400);
            }

            // Check if the buddy relationship exists
            $existingBuddy = Buddies::where('user_id', $buddyId)
                ->where('buddy_id', $userId)
                ->where('status', 'pending')
                ->first();

            if (!$existingBuddy) {
                return response()->json(['message' => 'Buddy relationship not found.'], 404);
            }

            // Update the status of the buddy relationship to 'accepted'
            $existingBuddy->status = 'accepted';
            $existingBuddy->save();

            return response()->json(['message' => 'Buddy request accepted successfully.', 'buddy' => $existingBuddy], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    // Reject Buddy
    public function rejectBuddy(Request $request, $buddyId)
    {
        try {
            // Get authenticated user
            $user = auth()->user();

            // Check if user exists
            if (!$user) {
                return response()->json([
                    "status" => false,
                    "message" => "User not found"
                ], 404);
            }

            // Get the user id
            $userId = $user->id;

            // Find the buddy relationship
            $buddy = Buddies::where('user_id', $buddyId)
                ->where('buddy_id', $userId)
                ->where('status', 'pending')
                ->first();

            if (!$buddy) {
                return response()->json(['message' => 'Buddy relationship not found or already accepted/rejected.'], 404);
            }

            // Update the status to 'rejected'
            $buddy->status = 'rejected';
            $buddy->save();

            return response()->json(['message' => 'Buddy request rejected successfully.', 'buddy' => $buddy], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Remove Buddy
    public function removeBuddy(Request $request, $buddyId)
    {
        try {
            // Get authenticated user
            $user = auth()->user();

            // Check if user exists
            if (!$user) {
                return response()->json([
                    "status" => false,
                    "message" => "User not found"
                ], 404);
            }

            // Get the user id
            $userId = $user->id;

            // Find the buddy relationship
            $buddy = Buddies::where(function ($query) use ($userId, $buddyId) {
                $query->where('user_id', $userId)
                    ->where('buddy_id', $buddyId);
            })->orWhere(function ($query) use ($userId, $buddyId) {
                $query->where('user_id', $buddyId)
                    ->where('buddy_id', $userId);
            })->where('status', 'accepted')->first();

            if (!$buddy) {
                return response()->json(['message' => 'Buddy relationship not found.'], 404);
            }

            // Delete the buddy relationship
            $buddy->delete();

            return response()->json(['message' => 'Buddy relationship removed successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getBuddies(Request $request, $memberId = null)
    {
        try {
            // Check if memberId is provided
            if ($memberId) {
                // Retrieve the member based on the provided memberId
                $member = Member::find($memberId);

                // Check if the member exists
                if (!$member) {
                    return response()->json(['message' => 'Member not found'], 404);
                }
            } else {
                // If memberId is not provided, retrieve the member based on the authenticated user
                $member = Member::where('user_id', auth()->user()->id)->first();
                if (!$member) {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            }

            // Get the user associated with the member
            $user = $member->user;

            // Get all buddies where the user is involved (either as the user or as the buddy) and the status is accepted
            $buddies = Buddies::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('buddy_id', $user->id);
            })
                ->where('status', 'accepted')
                ->with(['user', 'buddy'])
                ->get();

            // Create a collection of unique buddies
            $uniqueBuddies = collect();

            foreach ($buddies as $buddy) {
                if ($buddy->user_id === $user->id) {
                    $uniqueBuddies->push($buddy->buddy);
                } else {
                    $uniqueBuddies->push($buddy->user);
                }
            }

            // Remove duplicate buddies
            $uniqueBuddies = $uniqueBuddies->unique('id')->values();

            return response()->json([
                'buddies' => $uniqueBuddies
            ], 200);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error getting buddies: ' . $e->getMessage());

            // Return a JSON response with a generic error message
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
