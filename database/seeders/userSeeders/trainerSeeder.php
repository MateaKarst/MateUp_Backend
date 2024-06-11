<?php

namespace Database\Seeders\userSeeders;

use App\Models\User;
use App\Models\Trainer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Exception;

class trainerSeeder extends Seeder
{

    public function run(): void
    {
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
    
        // List of addresses
        $addresses = [
            'Bruul 107, 2800 Mechelen',
            'Zwartebeekstraat 14, 2800 Mechelen',
            'Sint-Jacobsstraat 1, 2800 Mechelen',
        ];
    
        // Ensure each address is used at least twice
        $addressUsage = array_fill(0, count($addresses), 0);
        $requiredUsage = array_fill(0, count($addresses), 2);
        $remainingUsers = count($trainerData);
    
        // Generate trainers
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
    
            // Assign an address ensuring each is used at least twice
            $address = null;
            for ($i = 0; $i < count($addresses); $i++) {
                if ($addressUsage[$i] < $requiredUsage[$i]) {
                    $address = $addresses[$i];
                    $addressUsage[$i]++;
                    break;
                }
            }
    
            // If all addresses have been used at least twice, select any address
            if ($address === null) {
                $address = $addresses[array_rand($addresses)];
            }
    
            $trainer = Trainer::create([
                'user_id' => $user->id,
                'home_club_address' => $address,
                'expertise' => $data['expertise'],
                'education' => $data['education'],
                'languages' => $data['languages'],
                'content_about' => $data['content_about'],
                'stripe_id' => $userTrainerFaker->unique()->numberBetween(1000000000, 9999999999),
                'stripe_url' => $userTrainerFaker->unique()->url,
                'rate_currency' => 'EUR',
                'rate_amount' => $userTrainerFaker->randomFloat(2, 10, 100),
            ]);
    
            $remainingUsers--;
        }
    }
}
