<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        // Admin user profile is automatically created

        // Create member user
        User::create([
            'role' => 'member',
            'username' => 'MainMember',
            'email' => 'member@localhost',
            'password' => Hash::make('member'),
            'name' => 'Main',
            'surname' => 'Member',
            'phone' => '000000002',
        ]);
        // Member user profile is automatically created

        // Create trainer user
        $trainer = User::create([
            'role' => 'trainer',
            'username' => 'MainTrainer',
            'email' => 'trainer@localhost',
            'password' => Hash::make('trainer'),
            'name' => 'Trainer',
            'surname' => 'Trainer',
            'phone' => '000000003',
        ]);

        // Create trainer profile
        Trainer::create([
            'user_id' => $trainer->id,
            'home_club_address' => '1',
            'expertise' => 'Yoga',
            'education' => 'Yoga Degree',
            'languages' => 'English, Spanish',
            'content_about' => 'I am a trainer at basic fit',
            'stripe_id' => '000000',
            'stripe_url' => 'stripeTrainer@stripe.com',
            'rate_currency' => 'EUR',
            'rate_amount' => '300',
        ]);
    }
}
