<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Buddies;
use App\Http\Controllers\Api\BuddiesController;


class MemberController extends Controller
{

    // Create new member
    public function createMember(Request $request, $userId)
    {
        try {
            // Validate request
            $request->validate([
                'home_club_address' => 'required',
                'workout_types' => 'required',
                'level_fitness' => 'required',
                'workout_types' => 'required',
            ]);

            // Create member
            Member::create([
                'user_id' => $userId,
                'home_club_address' => $request->home_club_address,
                'level_fitness' => $request->level_fitness,
                'workout_types' => $request->workout_types,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get member
            $member = Member::where('user_id', $userId)->first();

            // Check if member exists
            if ($member) {
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Member created successfully',
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Member not created',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get member
    public function getMember($memberId)
    {
        try {
            // Get member
            $member = Member::where('id', $memberId)->first();

            // Check if member exists
            if ($member) {
                // Manually load user relationship
                $member->load('user');

                // Return success response with user information included
                return response()->json([
                    'status' => true,
                    'member' => $member,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Member not found',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Update member
    public function updateMember(Request $request, $memberId = null)
    {
        try {
            // Validate request
            $request->validate([
                'home_club_address' => 'required',
                'level_fitness' => 'required',
                'workout_types' => 'required',
            ]);

            // Get member
            if ($memberId) {
                // Get member by member ID
                $member = Member::where('id', $memberId)->first();
            } else {
                // Get member from authenticated user
                $member = Member::where('user_id', auth()->user()->id)->first();
            }

            // Check if member exists
            if ($member) {
                // Update member
                $member->update([
                    'home_club_address' => $request->home_club_address,
                    'level_fitness' => $request->level_fitness,
                    'workout_types' => $request->workout_types,
                    'updated_at' => now(),
                ]);

                // Eager load user information
                $member->load('user');

                // Return success response with user information included
                return response()->json([
                    'status' => true,
                    'message' => 'Member updated successfully',
                    'member' => $member,
                ]);
            }

            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Member not updated',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Get all members
    public function getAllMembers(Request $request)
    {
        try {
            //     // Get the filter values from the request
            //     $home_club_address = $request->input('home_club_address');
            //     $level_fitness = $request->input('level_fitness');
            //     $workout_types = $request->input('workout_types');

            //     // Create the query
            //     $query = Member::with('user');

            //     // Add the filter conditions if they are provided
            //     if ($home_club_address) {
            //         $home_club_address = trim($home_club_address);
            //         $query->where('home_club_address', $home_club_address);
            //     }

            //     if ($level_fitness) {
            //         $level_fitness = trim($level_fitness);
            //         $query->where('level_fitness', $level_fitness);
            //     }

            //     // Execute the query to get all members initially
            //     $members = $query->get();

            //     // Filter by workout types if provided
            //     if ($workout_types) {
            //         $workoutTypesArray = explode(',', $workout_types);
            //         $workoutTypesArray = array_map('trim', $workoutTypesArray); // Trim whitespace from each value

            //         $members = $members->filter(function ($member) use ($workoutTypesArray) {
            //             $memberWorkoutTypes = explode(',', $member->workout_types);
            //             $memberWorkoutTypes = array_map('trim', $memberWorkoutTypes);
            //             foreach ($workoutTypesArray as $workoutType) {
            //                 if (in_array($workoutType, $memberWorkoutTypes)) {
            //                     return true;
            //                 }
            //             }
            //             return false;
            //         });
            //     }

            // Get all the members
            $members = Member::with('user')->get();

            // Check if members exist
            if ($members->isNotEmpty()) {
                // Return success response
                return response()->json([
                    "status" => true,
                    "members" => $members->values() // Re-index the collection
                ]);
            }

            // Return error response
            return response()->json([
                "status" => false,
                "message" => "Members not found"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                // Catch any exceptions and return error response
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function getMemberClubMembers(Request $request, $memberId)
    {
        try {
            // Get user ID and member ID from the request
            $memberId = $memberId;
            $userId = Member::where('id', $memberId)->first()->user_id;

            // Fetch current user and home club address
            $currentUserResponse = $this->getMember($memberId);
            if (!$currentUserResponse->original['status']) {
                throw new \Exception($currentUserResponse->original['message']);
            }
            $currentUser = $currentUserResponse->original['member'];
            $homeClubAddress = $currentUser['home_club_address'];

            // Fetch all members
            $membersResponse = $this->getAllMembers(new Request());
            if (!$membersResponse->original['status']) {
                throw new \Exception($membersResponse->original['message']);
            }
            $allMembers = $membersResponse->original['members'];

            // Fetch buddy IDs
            $buddiesController = new BuddiesController();
            $buddiesResponse = $buddiesController->getBuddies(new Request(), $userId);
            $buddyIds = $buddiesResponse->original['buddies']->pluck('id')->toArray();

            // Filter members by home club address and non-buddy
            $filteredMembers = $allMembers->filter(function ($member) use ($homeClubAddress, $buddyIds) {
                return $member['home_club_address'] === $homeClubAddress && !in_array($member['id'], $buddyIds);
            });

            return response()->json([
                'status' => true,
                'members' => $filteredMembers->values() // Re-index the collection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getMatchingWorkoutsMembers(Request $request, $memberId)
    {
        try {
            // Get user ID and member ID from the request
            $memberId = $memberId;
            $userId = Member::where('id', $memberId)->first()->user_id;

            // Fetch current user and home club address
            $currentUserResponse = $this->getMember($memberId);
            if (!$currentUserResponse->original['status']) {
                throw new \Exception($currentUserResponse->original['message']);
            }
            $currentUser = $currentUserResponse->original['member'];
            $currentUserWorkoutTypes = array_map('trim', explode(',', $currentUser['workout_types']));

            // Fetch all members
            $membersResponse = $this->getAllMembers(new Request());
            if (!$membersResponse->original['status']) {
                throw new \Exception($membersResponse->original['message']);
            }
            $allMembers = $membersResponse->original['members'];

            // Fetch buddy IDs
            $buddiesController = new BuddiesController();
            $buddiesResponse = $buddiesController->getBuddies(new Request(), $userId);
            $buddyIds = $buddiesResponse->original['buddies']->pluck('id')->toArray();

            // Filter members by workout type and non-buddy
            $filteredMembers = $allMembers->filter(function ($member) use ($currentUserWorkoutTypes, $buddyIds) {
                $memberWorkoutTypes = array_map('trim', explode(',', $member['workout_types']));
                $matches = !empty(array_intersect($currentUserWorkoutTypes, $memberWorkoutTypes));

                return $matches && !in_array($member['id'], $buddyIds);
            });

            return response()->json([
                'status' => true,
                'members' => $filteredMembers->values() // Re-index the collection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function searchingForMembers(Request $request, $memberId)
    {
        try {
            // Get user ID and member ID from the request
            $member = Member::find($memberId);
            if (!$member) {
                return response()->json(['status' => false, 'message' => 'Member not found'], 404);
            }
    
            $userId = $member->user_id;
    
            // Get all members
            $members = Member::with('user')->get();
    
            // Get all buddies of the authenticated user
            $buddies = Buddies::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('buddy_id', $userId);
            })
                ->where('status', 'accepted')
                ->get();
    
            // Create a set of buddy IDs for quick lookup
            $buddyIds = $buddies->map(function ($buddy) use ($userId) {
                return $buddy->user_id === $userId ? $buddy->buddy_id : $buddy->user_id;
            })->toArray();
    
            // Add is_buddy property to each member
            $members = $members->map(function ($member) use ($buddyIds) {
                $member->is_buddy = in_array($member->user_id, $buddyIds);
                return $member;
            });
    
            return response()->json(['status' => true, 'members' => $members], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
}
