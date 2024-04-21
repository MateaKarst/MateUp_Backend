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
        try {
            $request->validate([
                'home_club_address' => 'required',
                'fitness_level' => 'required',
                'workout_types' => 'required',
            ]);

            Member::create([
                'user_id' => $userId,
                'home_club_address' => $request->home_club_address,
                'fitness_level' => $request->fitness_level,
                'workout_types' => $request->workout_types,
            ]);

            $member = Member::where('user_id', $userId)->first();

            if ($member) {
                return response()->json([
                    'status' => true,
                    'message' => 'Member created successfully',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Member not created',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get member
    public function getMember($userId)
    {
        try {
            $member = Member::where('user_id', $userId)->first();

            if ($member) {
                return response()->json([
                    'status' => true,
                    'member' => $member,
                ]);
            }

            if ($member) {
                return response()->json([
                    'status' => false,
                    'message' => 'Member not found',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Member not found',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Update member
    public function updateMember(Request $request, $userId)
    {
        try {
            $request->validate([
                'home_club_address' => 'required',
                'fitness_level' => 'required',
                'workout_types' => 'required',
            ]);

            $member = Member::where('user_id', $userId)->first();

            if ($member) {
                $member->update([
                    'home_club_address' => $request->home_club_address,
                    'fitness_level' => $request->fitness_level,
                    'workout_types' => $request->workout_types,
                ]);
            }

            if ($member) {
                return response()->json([
                    'status' => true,
                    'message' => 'Member updated successfully',
                    'member' => $member,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Member not updated',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Delete member
    public function deleteMember($userId)
    {
        try {
            $member = Member::where('user_id', $userId)->first();

            if ($member) {
                $member->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Member deleted successfully',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Member not deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get all members
    public function getAllMembers()
    {
        try {
            $members = Member::all();

            if ($members) {
                return response()->json([
                    'status' => true,
                    'members' => $members,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'No members found',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
