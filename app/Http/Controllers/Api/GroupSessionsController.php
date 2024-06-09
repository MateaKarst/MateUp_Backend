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
                'location' => 'required',
                'max_participants' => 'required',
                'session_date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            // Create session
            GroupSessions::create([
                'user_id' => $user,
                'trainer_id' => $trainer,
                'location' => $request->location,
                'max_participants' => $request->max_participants,
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

    // Update session
    public function updateGroupSession(Request $request, $sessionId)
    {
        try {
            // Validate request
            $request->validate([
                'location' => 'required',
                'max_participants' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            // Update session
            GroupSessions::where('id', $sessionId)->update([
                'location' => $request->location,
                'max_participants' => $request->max_participants,
                'session_date' => $request->session_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'updated_at' => now(),
            ]);

            // Get session
            $session = GroupSessions::where('id', $sessionId)->first();

            // Check if session exists
            if ($session) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Session updated successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Session not updated',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Delete session
    public function deleteGroupSession($sessionId)
    {
        try {
            // Delete session
            GroupSessions::where('id', $sessionId)->delete();

            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Session deleted successfully',
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
