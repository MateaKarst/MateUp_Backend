<?php

namespace Database\Seeders\groupSessionsSeeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Admin;
use App\Models\GroupSessions;
use App\Models\Sessions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;


class groupSessionsSeeder extends Seeder
{
    public function run()
    {
        // Get all trainers
        $trainers = Trainer::all();
    
        // Create sessions for each trainer
        foreach ($trainers as $trainer) {
            // Generate a random number of sessions between 1 and 3
            $numSessions = rand(1, 3);
    
            // Get the trainer's home club address
            $home_club_address = $trainer->home_club_address;
    
            // Create sessions for the current trainer
            for ($i = 0; $i < $numSessions; $i++) {
                // Create fake data for the session
                $sessionsFaker = Faker::create();
                $location = $home_club_address;
                $maxParticipants = $sessionsFaker->numberBetween(5, 20);
    
                // Generate a start date between today and one month from now
                $startDate = $sessionsFaker->dateTimeBetween('today', '+1 month')->format('Y-m-d');
    
                // Generate a start time
                $startTime = $sessionsFaker->time($format = 'H:i:s');
    
                // Generate an end time later than start time
                $endTime = $sessionsFaker->time($format = 'H:i:s', $max = '23:59:59');
    
                // Create the session
                GroupSessions::create([
                    'user_id' => $trainer->user_id,
                    'trainer_id' => $trainer->id,
                    'location' => $location,
                    'max_participants' => $maxParticipants,
                    'session_date' => $startDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
