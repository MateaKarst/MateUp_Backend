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
        $request->validate([
            'expertise' => 'required',
            'education' => 'required',
            'languages' => 'required',
            'rate_currency' => 'required',
            'rate_amount' => 'required',
            'content_about' => 'required',
        ]);

        Trainer::create([
            'user_id' => $userId,
            'expertise' => $request->expertise,
            'education' => $request->education,
            'stripe_id'=> '234567',
            'stripe_url' => 'stripeTrainer@stripe.com',
            'languages' => $request->languages,
            'rate_currency' => $request->rate_currency,
            'rate_amount' => $request->rate_amount,
            'content_about' => $request->content_about
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trainer created successfully',
        ]);
    }

    // Update trainer
    public function updateTrainer(Request $request, $userId)
    {
        $request->validate([
            'expertise' => 'required',
            'education' => 'required',
            'languages' => 'required',
            'rate_currency' => 'required',
            'rate_amount' => 'required',
            'content_about' => 'required',
        ]);

        $trainer = Trainer::where('user_id', $userId)->first();

        if ($trainer) {
            $trainer->update([
                'expertise' => $request->expertise,
                'education' => $request->education,
                'languages' => $request->languages,
                'rate_currency' => $request->rate_currency,
                'rate_amount' => $request->rate_amount,
                'content_about' => $request->content_about
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Trainer updated successfully',
                'trainer' => $trainer
            ]);
        };
    }
}
