<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

use function PHPSTORM_META\map;

class DatabaseSeeder extends Seeder
{
    // Run the database seeds.
    public function run(): void
    {
        // // Create admin user
        // User::create([
        //     'role' => 'admin',
        //     'username' => 'MainAdmin',
        //     'email' => 'admin@localhost',
        //     'password' => Hash::make('admin'),
        //     'name' => 'Main',
        //     'surname' => 'Admin',
        //     'phone' => '000000001',
        // ]);

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


        // TRAINER USERS
        $trainerData = [
            [
                'expertise' => 'Strength training',
                'education' => "Bachelor's in Exercise Science",
                'languages' => 'English, Spanish',
                'content_about' => 'Building muscle and proper form.',
            ],
            [
                'expertise' => 'HIIT',
                'education' => 'Certified HIIT Trainer',
                'languages' => 'English',
                'content_about' => 'HIIT workouts and endurance training.',
            ],
            [
                'expertise' => 'Yoga',
                'education' => 'Yoga Alliance Certified Instructor',
                'languages' => 'English, French',
                'content_about' => 'Stress relief and mindfulness through yoga.',
            ],
            [
                'expertise' => 'HIIT',
                'education' => 'Certified HIIT Trainer',
                'languages' => 'English',
                'content_about' => 'Creating balanced meal plans for weight loss and muscle gain.',
            ],
            [
                'expertise' => 'CrossFit',
                'education' => 'CrossFit Level 1 Trainer',
                'languages' => 'English',
                'content_about' => 'Functional movements for everyday life.',
            ],
            [
                'expertise' => 'Martial arts',
                'education' => 'Black Belt in Taekwondo',
                'languages' => 'English, Korean',
                'content_about' => 'Self-defense techniques and discipline.',
            ],
            [
                'expertise' => 'Core Workouts',
                'education' => 'Certified Core Instructor',
                'languages' => 'English, German',
                'content_about' => 'Strengthening the core and improving posture.',
            ],
            [
                'expertise' => 'CrossFit',
                'education' => "Master's in CrossFit",
                'languages' => 'English',
                'content_about' => 'Enhancing athletic performance through specialized training.',
            ],
            [
                'expertise' => 'Cycling',
                'education' => 'Cycling Alliance Certified Instructor',
                'languages' => 'English',
                'content_about' => 'High-energy spin classes and improving cardiovascular health.',
            ],
            [
                'expertise' => 'CrossFit',
                'education' => 'CrossFit Level 2 Trainer',
                'languages' => 'English',
                'content_about' => 'Varied workouts for overall fitness and athleticism.',
            ],
        ];

        $profileImagesTrainer = [
            'https://images.pexels.com/photos/1278566/pexels-photo-1278566.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1499327/pexels-photo-1499327.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1130626/pexels-photo-1130626.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1821095/pexels-photo-1821095.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1819483/pexels-photo-1819483.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/2379005/pexels-photo-2379005.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/1239288/pexels-photo-1239288.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/2613260/pexels-photo-2613260.jpeg?auto=compress&cs=tinysrgb&w=600',
            'https://images.pexels.com/photos/6801642/pexels-photo-6801642.jpeg?auto=compress&cs=tinysrgb&w=600',
        ];
        $usedProfileImagesTrainer = [];

        $userTrainerFaker = Faker::create();

        foreach ($trainerData as $data) {
            $name = $userTrainerFaker->firstName;
            $surname = $userTrainerFaker->lastName;
            $username = $name . ' ' . $surname;
            $password = 'password';

            // Ensure unique username
            $uniqueUsername = $username;
            $index = 1;
            while (User::where('username', $uniqueUsername)->exists()) {
                $uniqueUsername = $username . ' ' . $index++;
            }

            // Ensure unique email
            $email = $userTrainerFaker->unique()->safeEmail;

            // Generate random phone number (as string of digits)
            $phone = $userTrainerFaker->unique()->numberBetween(1000000000, 9999999999);


            // Profile image URL
            $availableImages = array_diff($profileImagesTrainer, $usedProfileImagesTrainer);
            if (empty($availableImages)) {
                throw new Exception('No available images left to assign.');
            }
            $randomImageUrlTrainer = $availableImages[array_rand($availableImages)];

            // Insert user data into database
            $user = User::create([
                'role' => 'trainer',
                'username' => $uniqueUsername,
                'email' => $email,
                'password' => Hash::make($password), // default password
                'name' => $name,
                'surname' => $surname,
                'phone' => $phone,
                'bio' => $userTrainerFaker->optional()->paragraph,
                'profile_image_url' => $randomImageUrlTrainer,
                'facebook' => null,
                'instagram' => null,
                'twitter' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            $usedProfileImagesTrainer[] = $randomImageUrlTrainer;

            $addresses = [
                'Bruul 107, 2800 Mechelen',
                'Zwartebeekstraat 14, 2800 Mechelen',
                'Sint-Jacobsstraat 1, 2800 Mechelen',
            ];

            $address = $addresses[array_rand($addresses)];


            $trainer = Trainer::create([
                'user_id' => $user->id,
                'home_club_address' =>   $address,
                'expertise' => $data['expertise'],
                'education' => $data['education'],
                'languages' => $data['languages'],
                'content_about' => $data['content_about'],
                'stripe_id' =>  $userTrainerFaker->unique()->numberBetween(1000000000, 9999999999),
                'stripe_url' =>  $userTrainerFaker->unique()->url,
                'rate_currency' => 'EUR',
                'rate_amount' => $userMemberFaker->randomFloat(2, 10, 100),
            ]);
        }

        // BUDDIES
        $buddiesFaker = Faker::create();

        // Get all users
        $users = User::all();

        // Generate buddies
        foreach ($users as $user) {
            // Determine the number of buddies for each user
            $buddiesCount = $buddiesFaker->numberBetween(1, 5);

            // Get random buddies
            $buddies = $users->random($buddiesCount)->pluck('id')->toArray();

            foreach ($buddies as $buddyId) {
                // Ensure the buddy relationship is unique and not with themselves
                if ($user->id !== $buddyId && !DB::table('buddies')->where([
                    ['user_id', $user->id],
                    ['buddy_id', $buddyId],
                ])->exists()) {
                    DB::table('buddies')->insert([
                        'user_id' => $user->id,
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
