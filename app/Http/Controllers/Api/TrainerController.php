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
                'timestamp' => now(),
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
    public function getTrainer($trainerId = null)
    {
        try {
            // Get trainer
            if ($trainerId) {
                // Get trainer by user ID
                $trainer = Trainer::where('id', $trainerId)->first();
            } else {
                // Get id from authenticated use
                $trainer = Trainer::where('user_id', auth()->user()->id)->first(); // Get trainer from authenticated use               
            }

            // Check if trainer exists
            if ($trainer) {
                // Manually load user relationship
                $trainer->load('user');

                // Return success response with user information included
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
    public function updateTrainer(Request $request, $userId = null)
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
                $trainer = Trainer::where('id', $userId)->first();
            } else {
                // Get trainer from authenticated user
                $trainer = Trainer::where('user_id', auth()->user()->id)->first();
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
                    'updated_at' => now(),
                ]);

                // Manually load user relationship
                $trainer->load('user');

                // Return success response with user information included
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

    // Get all trainers
    public function getAllTrainers(Request $request)
    {
        try {
            // Get the filter values form request
            $home_club_address = $request->input('home_club_address');
            $expertise = $request->input('expertise');

            // Create the query
            $query = Trainer::with('user');

            // Add the filter conditions if they are provided
            if ($home_club_address) {
                $home_club_address = trim($home_club_address);
                $query->where('home_club_address', $home_club_address);
            }

            if ($expertise) {
                $expertise = trim($expertise);
                $query->where('expertise', $expertise);
            }

            // Execute the query to get all members initially
            $trainers = $query->get();

            // Check if trainers exist
            if ($trainers->isNotEmpty()) {
                // Return success response with user information included
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
