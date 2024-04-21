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
            $request->validate([
                'home_club_address' => 'required',
                'expertise' => 'required',
                'education' => 'required',
                'languages' => 'required',
                'content_about' => 'required',
                'rate_currency' => 'required',
                'rate_amount' => 'required',
            ]);

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

            $trainer = Trainer::where('user_id', $userId)->first();

            if ($trainer) {
                return response()->json([
                    'status' => true,
                    'message' => 'Trainer created successfully',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Trainer not created',
            ]);
        } catch (\Exception $e) {
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
            $trainer = Trainer::where('user_id', $userId)->first();

            if ($trainer) {
                return response()->json([
                    'status' => true,
                    'trainer' => $trainer,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Trainer not found',
            ]);
        } catch (\Exception $e) {
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
            $request->validate([
                'home_club_address' => 'required',
                'expertise' => 'required',
                'education' => 'required',
                'languages' => 'required',
                'content_about' => 'required',
                'rate_currency' => 'required',
                'rate_amount' => 'required',
            ]);

            $trainer = Trainer::where('user_id', $userId)->first();

            if ($trainer) {
                $trainer->update([
                    'home_club_address' => $request->home_club_address,
                    'expertise' => $request->expertise,
                    'education' => $request->education,
                    'languages' => $request->languages,
                    'rate_currency' => $request->rate_currency,
                    'rate_amount' => $request->rate_amount,
                    'content_about' => $request->content_about,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Trainer updated successfully',
                    'trainer' => $trainer,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Trainer not found',
            ]);
        } catch (\Exception $e) {
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
            $trainers = Trainer::all();

            if ($trainers) {
                return response()->json([
                    'status' => true,
                    'trainers' => $trainers,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'No trainers found',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
