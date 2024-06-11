<?php

namespace Database\Seeders\userSeeders;

use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Exception;

class memberSeeder extends Seeder
{

    public function run(): void
    {
        // MEMBER USERS
        $userMemberFaker = Faker::create();

        $profileImagesMember = [
            'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1310522/pexels-photo-1310522.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1043471/pexels-photo-1043471.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/432059/pexels-photo-432059.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/943084/pexels-photo-943084.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1040880/pexels-photo-1040880.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/428364/pexels-photo-428364.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/678783/pexels-photo-678783.jpeg?auto=compress&cs=tinysrgb&w=600',
            "https://images.pexels.com/photos/1484801/pexels-photo-1484801.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/889511/pexels-photo-889511.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/2340978/pexels-photo-2340978.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/1035673/pexels-photo-1035673.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/1040882/pexels-photo-1040882.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/4429279/pexels-photo-4429279.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/2884842/pexels-photo-2884842.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/2460227/pexels-photo-2460227.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/3778212/pexels-photo-3778212.jpeg?auto=compress&cs=tinysrgb&w=600",
            "https://images.pexels.com/photos/2080382/pexels-photo-2080382.jpeg?auto=compress&cs=tinysrgb&w=600",
        ];
        $usedProfileImagesMember = [];

        // Create 20 members
        for ($i = 0; $i < 20; $i++) {
            $name = $userMemberFaker->firstName;
            $surname = $userMemberFaker->lastName;
            $username = $name . ' ' . $surname;
            $password = 'password';

            // Ensure unique username
            $uniqueUsername = $username;
            $index = 1;
            while (User::where('username', $uniqueUsername)->exists()) {
                $uniqueUsername = $username . ' ' . $index++;
            }

            // Ensure unique email
            $email = $userMemberFaker->unique()->safeEmail;

            // Generate random phone number (as string of digits)
            $phone = $userMemberFaker->unique()->numberBetween(1000000000, 9999999999);

            // Profile image URL
            $availableImages = array_diff($profileImagesMember, $usedProfileImagesMember);
            if (empty($availableImages)) {
                throw new Exception('No available images left to assign.');
            }
            $randomImageUrl = $availableImages[array_rand($availableImages)];


            // Insert user data into database
            $user = User::create([
                'role' => 'member',
                'username' => $uniqueUsername,
                'email' => $email,
                'password' => Hash::make($password), // default password
                'name' => $name,
                'surname' => $surname,
                'phone' => $phone,
                'bio' => $userMemberFaker->optional()->paragraph,
                'profile_image_url' => $randomImageUrl,
                'facebook' => null,
                'instagram' => null,
                'twitter' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            $usedProfileImagesMember[] = $randomImageUrl;

            $workouts = [
                'Cardio Workouts',
                'Strength Training',
                'Core Workouts',
                'Yoga',
                'CrossFit',
                'HIIT',
            ];

            // Retrieve all members
            $members = Member::all();
            foreach ($members as $member) {
                // Select two random workout types
                $randomWorkouts = array_rand(array_flip($workouts), 2);

                // Update the member with the selected workout types
                $member->workout_types = implode(', ', $randomWorkouts);
                $member->save();
            }
        }
    }
}