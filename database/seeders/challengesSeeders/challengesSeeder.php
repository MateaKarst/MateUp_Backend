<?php

namespace Database\Seeders\challengesSeeders;

use App\Models\User;
use App\Models\Challenges;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use function PHPSTORM_META\map;

class ChallengesSeeder extends Seeder
{
    public function run()
    {
        // Get all users except those with the role "admin"
        $users = User::where('role', '!=', 'admin')->get();

        // Create challenges for each user
        foreach ($users as $user) {
            // Define the number of challenges to create for each user
            $numChallenges = 2;

            // get the home_club_address of the member ot trainer user
            $userType = $user->role;

            if ($userType == 'member') {
                $location = $user->member->home_club_address;
            } elseif ($userType == 'trainer') {
                $location = $user->trainer->home_club_address;
            }


            // Define the details of each challenge
            $challengeDetails = [
                [
                    'name' => 'Muscle Building',
                    'goal' => 'Build muscle mass and strength',
                    'workout_category' => 'Strength Training',
                ],
                [
                    'name' => 'Endurance Training',
                    'goal' => 'Improve cardiovascular endurance',
                    'workout_category' => 'Cardio Workouts',
                ],
                [
                    'name' => 'Functional Fitness',
                    'goal' => 'Enhance functional movement patterns',
                    'workout_category' => 'Core Workouts',
                ],
                [
                    'name' => 'HIIT',
                    'goal' => 'Maximize calorie burn in short bursts',
                    'workout_category' => 'HIIT',
                ],
                [
                    'name' => 'Core Strengthening',
                    'goal' => 'Strengthen core muscles and stability',
                    'workout_category' => 'Core Workouts',
                ],
                [
                    'name' => 'Flexibility',
                    'goal' => 'Increase flexibility and joint range of motion',
                    'workout_category' => 'Yoga',
                ],
                [
                    'name' => 'Cross Training',
                    'goal' => 'Improve heart health and stamina',
                    'workout_category' => 'CrossFit',
                ],
                [
                    'name' => 'Bodyweight Workout',
                    'goal' => 'Use body weight for resistance training',
                    'workout_category' => 'Strength Training',
                ],
            ];

            // List of session image URLs
            $challengeImages = [
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


            // Create challenges for the current user
            for ($i = 0; $i < $numChallenges; $i++) {
                // Generate fake data for the challenge
                $challengeFaker = Faker::create();

                // Randomly select a challenge image
                $randomIndex = array_rand($challengeImages);
                $challengeImage = $challengeImages[$randomIndex];

                // Randomly select a challenge detail
                $randomIndex = array_rand($challengeDetails);
                $challengeDetail = $challengeDetails[$randomIndex];

                // Either input a frequency or time based challenge
                // $isFrequencyBased = $challengeFaker->boolean();

                // Convert time-based durations to valid time format
                $timeBasedDurations = [
                    '30 minutes' => '00:30:00',
                    '1 hour' => '01:00:00',
                    '2 hours' => '02:00:00',
                ];

                // Generate random start and end dates
                $startDate = $challengeFaker->dateTimeBetween('2024-01-01', '2024-12-31')->format('Y-m-d');
                $endDate = $challengeFaker->dateTimeBetween($startDate, '2025-12-31')->format('Y-m-d');

                // Create the challenge
                Challenges::create([
                    'user_id' => $user->id,
                    'name' => $challengeDetail['name'],
                    'club_address' => $location,
                    'goal' => $challengeDetail['goal'],
                    'challenge_image_url' => $challengeImage,
                    'workout_category' => $challengeDetail['workout_category'],
                    // 'frequency_based' => $isFrequencyBased ? $challengeFaker->randomElement(['Daily', 'Weekly', 'Monthly']) : null,
                    // 'time_based' => !$isFrequencyBased ? $challengeFaker->randomElement($timeBasedDurations) : null,
                    'time_based' => $challengeFaker->randomElement(array_values($timeBasedDurations)),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
