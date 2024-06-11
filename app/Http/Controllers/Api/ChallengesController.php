<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\challenges;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Member;

class ChallengesController extends Controller
{
    // Create challenge
    public function createGroupSession(Request $request, $userId)
    {
        try {
            // check if the user exists
            $user = User::where('id', $userId)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ]);
            }

            // Validate request
            $request->validate([
                'challenge_image_url' => 'required',
                'club_address' => 'required',
                'goal' => 'required',
                'workout_category' => 'required',
                'start_date'=> 'required',
                'end_date' => 'required',
                'year' => 'required',
                // 'frequency_based' => 'optional',
                // 'time_based' => 'optional',
            ]);

            // Create challenge
            Challenges::create([
                'user_id' => $user,
                'challenge_image_url' => $request->challenge_image_url,
                'goal' => $request->goal,
                'workout_category' => $request->workout_category,
                // 'frequency_based' => $request->frequency_based,
                'time_based' => $request->time_based,
                'club_address' => $request->club_address,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get challenge
            $challenge = Challenges::where('user_id', $user)->first();

            // Check if challenge exists
            if ($challenge) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Challenge created successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Challenge not created',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get member club challenges
    public function getMemberClubChallenges(Request $request, $memberId)
    {
        try {

            // Get the member
            $member = Member::where('id', $memberId)->first();

            // Retrieve the home club address of the member
            $homeClubAddress = $member->home_club_address;

            // Retrieve all challenges with the same workout category as the home club address
            $challenges = Challenges::where('club_address', $homeClubAddress)->get();

            // Return the challenges as a JSON response
            return response()->json([
                'status' => true,
                'challenges' => $challenges,
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
