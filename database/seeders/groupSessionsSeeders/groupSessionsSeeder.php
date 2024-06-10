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
            $numSessions = rand(2, 4);

            // Get the trainer's home club address
            $home_club_address = $trainer->home_club_address;

            // List of session goals
            $sessionGoals = [
                'Muscle Building',
                'Weight Loss',
                'Endurance Training',
                'Functional Fitness',
                'High-Intensity Interval Training (HIIT)',
                'Core Strengthening',
                'Flexibility and Mobility',
                'Cardiovascular Conditioning',
                'Cross Training',
                'Bodyweight Workout'
            ];

            // List of session image URLs
            $SessionImages = [
                'https://images.pexels.com/photos/3490363/pexels-photo-3490363.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/3757376/pexels-photo-3757376.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/2261477/pexels-photo-2261477.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/1552106/pexels-photo-1552106.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/68468/pexels-photo-68468.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/6389075/pexels-photo-6389075.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/3289711/pexels-photo-3289711.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/853247/pexels-photo-853247.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/2294361/pexels-photo-2294361.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/1431282/pexels-photo-1431282.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/864939/pexels-photo-864939.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/416778/pexels-photo-416778.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/317155/pexels-photo-317155.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/7592377/pexels-photo-7592377.jpeg?auto=compress&cs=tinysrgb&w=300',
                'https://images.pexels.com/photos/7592371/pexels-photo-7592371.jpeg?auto=compress&cs=tinysrgb&w=300',
            ];

            // Create sessions for the current trainer
            for ($i = 0; $i < $numSessions; $i++) {
                // Create fake data for the session
                $sessionsFaker = Faker::create();
                $location = $home_club_address;
                $maxParticipants = $sessionsFaker->numberBetween(2, 10);
                $currentParticipants = $sessionsFaker->numberBetween(0, $maxParticipants);
                $availableSpots = $maxParticipants - $currentParticipants;

                // Generate a start date between today and one month from now
                $startDate = $sessionsFaker->dateTimeBetween('today', '+1 month')->format('Y-m-d');

                // Generate a start time
                $startTime = $sessionsFaker->time($format = 'H:i:s');

                // Generate an end time later than start time
                $endTime = $sessionsFaker->time($format = 'H:i:s', $max = '23:59:59');

                // Randomly select a session goal
                $sessionGoal = $sessionGoals[array_rand($sessionGoals)];

                // Randomly select a session image
                $SessionImage = $SessionImages[array_rand($SessionImages)];

                // Create the session
                GroupSessions::create([
                    'user_id' => $trainer->user_id,
                    'trainer_id' => $trainer->id,
                    'session_image_url' => $SessionImage,
                    'goal' => $sessionGoal,
                    'club_address' => $location,
                    'max_participants' => $maxParticipants,
                    'available_spots' => $availableSpots,
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
