<?php

namespace Database\Seeders\userSeeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class buddiesSeeder extends Seeder
{

    public function run(): void
    {
        // BUDDIES
        $buddiesFaker = Faker::create();

        // Get all members
        $members = Member::all();

        // Generate buddies for each member
        foreach ($members as $member) {
            // Determine the number of buddies for each member
            $buddiesCount = $buddiesFaker->numberBetween(1, 5);

            // Get random members to be buddies with
            $buddyCandidates = Member::where('id', '!=', $member->id)->pluck('id')->toArray();

            // Shuffle the array of buddies to ensure randomness
            shuffle($buddyCandidates);

            // Select a subset of buddies from the shuffled array
            $buddies = array_slice($buddyCandidates, 0, $buddiesCount);

            // Insert buddies into the database
            foreach ($buddies as $buddyId) {
                // Retrieve the user ID of the member
                $userId = $member->user_id;

                // Ensure the buddy relationship is unique
                if (!DB::table('buddies')->where([
                    ['user_id', $userId],
                    ['buddy_id', $buddyId],
                ])->exists()) {
                    DB::table('buddies')->insert([
                        'user_id' => $userId,
                        'buddy_id' => $buddyId,
                        'status' => 'accepted',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
