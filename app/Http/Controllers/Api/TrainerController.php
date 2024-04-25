<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;

class TrainerController extends Controller
{

    // Create new trainer
    public function createTrainer(Request $request, $userId)
    {
        try {
            // Validate request
            $request->validate([
                'home_club_address' => 'required',
                'expertise' => 'required',
                'education' => 'required',
                'languages' => 'required',
                'content_about' => 'required',
                'rate_currency' => 'required',
                'rate_amount' => 'required',
            ]);

            // Create trainer
            Trainer::create([
                'user_id' => $userId,
                'home_club_address' => $request->home_club_address,
                'expertise' => $request->expertise,
                'education' => $request->education,
                'languages' => $request->languages,
                'content_about' => $request->content_about,
                'rate_currency' => $request->rate_currency,
                'rate_amount' => $request->rate_amount,
                'stripe_id' => '234567',
                'stripe_url' => 'stripeTrainer@stripe.com',
            ]);

            // Get trainer
            $trainer = Trainer::where('user_id', $userId)->first();

            // Check if trainer exists
            if ($trainer) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Trainer created successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Trainer not created',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get trainer
    public function getTrainer($userId)
    {
        try {
            // Get trainer
            if ($userId) {
                // Get trainer by user ID
                $trainer = Trainer::where('user_id', $userId)->first();
            } else {
                // Get trainer from authenticated user
                $userId = auth()->user()->id;
                $trainer = Trainer::where('user_id', $userId)->first();
            }

            // Check if trainer exists
            if ($trainer) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'trainer' => $trainer,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Trainer not found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Update trainer
    public function updateTrainer(Request $request, $userId)
    {
        try {
            // Validate request
            $request->validate([
                'home_club_address' => 'required',
                'expertise' => 'required',
                'education' => 'required',
                'languages' => 'required',
                'content_about' => 'required',
                'rate_currency' => 'required',
                'rate_amount' => 'required',
            ]);

            // Get trainer
            if ($userId) {
                // Get trainer by user ID
                $trainer = Trainer::where('user_id', $userId)->first();
            } else {
                // Get trainer from authenticated user
                $userId = auth()->user()->id;
                $trainer = Trainer::where('user_id', $userId)->first();
            }

            // Check if trainer exists
            if ($trainer) {
                // Update trainer
                $trainer->update([
                    'home_club_address' => $request->home_club_address,
                    'expertise' => $request->expertise,
                    'education' => $request->education,
                    'languages' => $request->languages,
                    'rate_currency' => $request->rate_currency,
                    'rate_amount' => $request->rate_amount,
                    'content_about' => $request->content_about,
                ]);

                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Trainer updated successfully',
                    'trainer' => $trainer,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Trainer not found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Delete trainer
    public function deleteTrainer($userId)
    {
        try {
            // Get trainer
            if ($userId) {
                // Get trainer by user ID
                $trainer = Trainer::where('user_id', $userId)->first();
            } else {
                // Get trainer from authenticated user
                $userId = auth()->user()->id;
                $trainer = Trainer::where('user_id', $userId)->first();
            }

            // Check if trainer exists
            if ($trainer) {
                // Delete trainer
                $trainer->delete();

                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Trainer deleted successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Trainer not deleted',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get all trainers
    public function getAllTrainers()
    {
        try {
            // Get trainers
            $trainers = Trainer::all();

            // Check if trainers exist
            if ($trainers) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'trainers' => $trainers,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'No trainers found',
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
