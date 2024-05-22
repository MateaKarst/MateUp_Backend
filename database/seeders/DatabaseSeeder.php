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
        // Create admin user
        User::create([
            'role' => 'admin',
            'username' => 'MainAdmin',
            'email' => 'admin@localhost',
            'password' => Hash::make('admin'),
            'name' => 'Main',
            'surname' => 'Admin',
            'phone' => '000000001',
        ]);

        // MEMBER USERS
        $userMemberFaker = Faker::create();

        $profileImagesMember = [
            'https://ogletree.com/app/uploads/people/Shirin-Aboujawde.jpg',
            'https://ogletree.com/app/uploads/people/abitbol-alexandre-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/kyle-t-abraham-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/justine-l-abrams-2023-04.jpg',
            'https://ogletree.com/app/uploads/2023/04/marielly-abzun-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/elizabeth-d-adamek-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/paul-lancaster-adams-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/kenneth-m-adams-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/alison-k-adelman-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/daniel-a-adlong-1-2023-04.jpg',

            // 'https://ogletree.com/app/uploads/people/margaret-carroll-alli-2023-04.jpg',
            // 'https://ogletree.com/app/uploads/people/emma-allison.jpg',
            // 'https://ogletree.com/app/uploads/people/amlani-fauzia-gray-2023-04.jpg',
            // 'https://ogletree.com/app/uploads/people/maria-anastas-2023-04.jpg',
            // 'https://ogletree.com/app/uploads/people/emily-rupp-anderson-2023-04.jpg',
            // 'https://ogletree.com/app/uploads/people/corie-j-anderson.jpg',
            // 'https://ogletree.com/app/uploads/people/andrews-ann-gray-2023-04.jpg',
            // 'https://ogletree.com/app/uploads/people/deborah-andrews-2023-04.jpg',
            // 'https://ogletree.com/app/uploads/people/omar-m-aniff-2023-04.jpg',
        ];
        $usedProfileImagesMember = [];

        for ($i = 0; $i < 10; $i++) {
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
            ];

            // Retrieve all members
            $members = Member::all();
            foreach ($members as $member) {
                // Select a random workout type
                $randomWorkout = $workouts[array_rand($workouts)];

                // Update the member with the selected workout type
                $member->workout_types = $randomWorkout;
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
                'expertise' => 'Cardiovascular conditioning',
                'education' => 'Certified Personal Trainer',
                'languages' => 'English',
                'content_about' => 'HIIT workouts and endurance training.',
            ],
            [
                'expertise' => 'Yoga and flexibility',
                'education' => 'Yoga Alliance Certified Instructor',
                'languages' => 'English, French',
                'content_about' => 'Stress relief and mindfulness through yoga.',
            ],
            [
                'expertise' => 'Nutrition coaching',
                'education' => 'Registered Dietitian',
                'languages' => 'English',
                'content_about' => 'Creating balanced meal plans for weight loss and muscle gain.',
            ],
            [
                'expertise' => 'Functional training',
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
                'expertise' => 'Pilates and core strength',
                'education' => 'Certified Pilates Instructor',
                'languages' => 'English, German',
                'content_about' => 'Strengthening the core and improving posture.',
            ],
            [
                'expertise' => 'Sports performance coaching',
                'education' => "Master's in Sports Science",
                'languages' => 'English',
                'content_about' => 'Enhancing athletic performance through specialized training.',
            ],
            [
                'expertise' => 'Indoor cycling',
                'education' => 'Spinning Instructor Certification',
                'languages' => 'English',
                'content_about' => 'High-energy spin classes and improving cardiovascular health.',
            ],
            [
                'expertise' => 'CrossFit and functional fitness',
                'education' => 'CrossFit Level 2 Trainer',
                'languages' => 'English',
                'content_about' => 'Varied workouts for overall fitness and athleticism.',
            ],
        ];

        $profileImagesTrainer = [
            'https://ogletree.com/app/uploads/people/daniel-a-adlong-1-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/rodolfo-r-agraz-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/carmen-m-aguado-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/leticia-letty-p-aguilar-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/hassan-ahtouch-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/jean-marc-albiol-2023-04.jpg',
            'https://ogletree.com/app/uploads/2023/04/heba-alhassan-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/allen-nathan-gray-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/justin-a-allen-2023-04.jpg',
            'https://ogletree.com/app/uploads/people/jerod-a-allen.jpg',
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

            $trainer = Trainer::create([
                'user_id' => $user->id,
                'home_club_address' => 1, // Assuming this data comes from somewhere
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
    }
}
