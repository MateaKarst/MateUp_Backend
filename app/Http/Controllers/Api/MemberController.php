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
        $request->validate([
            'fitness_level' => 'required',
            'workout_types' => 'required',
        ]);

        Member::create([
            'user_id' => $userId,
            'fitness_level' => $request->fitness_level,
            'workout_types' => $request->workout_types
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Member created successfully',
        ]);
    }

    // Update member
    public function updateMember(Request $request, $userId)
    {
        $request->validate([
            'fitness_level' => 'required',
            'workout_types' => 'required',
        ]);

        $member = Member::where('user_id', $userId)->first();

        if ($member) {
            $member->update([
                'fitness_level' => $request->fitness_level,
                'workout_types' => $request->workout_types
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Member updated successfully',
            'member' => $member
        ]);
    }
}
