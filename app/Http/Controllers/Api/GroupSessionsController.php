<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\User;
use App\Models\GroupSessions;

class GroupSessionsController extends Controller
{
    // Create new session
    public function createGroupSession(Request $request, $trainerId)
    {
        try {
            // check if the trainer exists
            $trainer = Trainer::where('user_id', $trainerId)->first();
            if (!$trainer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Trainer not found',
                ]);
            }

            $user = $trainer->user_id;

            // Validate request
            $request->validate([        
                'session_image_url' => 'required',
                'goal' => 'required',
                'club_address' => 'required',
                'max_participants' => 'required',
                'available_spots' => 'required',
                'session_date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            // Create session
            GroupSessions::create([
                'user_id' => $user,
                'trainer_id' => $trainer,
                'session_image_url' => $request->session_image_url,
                'goal' => $request->goal,
                'club_address' => $request->club_address,
                'max_participants' => $request->max_participants,
                'available_spots' => $request->available_spots,
                'session_date' => $request->session_date,                
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get session
            $session = GroupSessions::where('user_id', $user)->first();

            // Check if session exists
            if ($session) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Session created successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Session not created',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get session
    public function getGroupSession($sessionId = null)
    {
        try {
            // Get session
            if ($sessionId) {
                // Get session by user ID
                $session = GroupSessions::where('id', $sessionId)->first();
            } else {
                // Get session from authenticated user
                $session = GroupSessions::where('user_id', auth()->user()->id)->first();
            }

            // Check if session exists
            if ($session) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'session' => $session,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Session not found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get all sessions
    public function getAllGroupSessions(Request $request, $trainerId)
    {
        try {
            // Get all sessions
            $sessions = GroupSessions::where('trainer_id', $trainerId)->get();

            // Check if sessions exist
            if (!$sessions) {
                // Return error response
                return response()->json([
                    'status' => false,
                    'message' => 'No sessions found',
                ]);
            }

            // get ther trainer
            $trainer = Trainer::where('id', $trainerId)->first();

            // Return success response
            return response()->json([
                'status' => true,
                'trainer' => $trainer,
                'sessions' => $sessions,
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
