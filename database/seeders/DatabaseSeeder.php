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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'role' => 'admin',
            'username' => 'MainAdmin',
            'email' => 'admin@localhost',
            'password' => Hash::make('admin'),
            'name' => 'Main',
            'surname' => 'Admin',
            'phone' => '000000001',
        ]);

        User::create([
            'role' => 'member',
            'username' => 'MainMember',
            'email' => 'member@localhost',
            'password' => Hash::make('member'),
            'name' => 'Main',
            'surname' => 'Member',
            'phone' => '000000002',
        ]);

        $trainer = User::create([
            'role' => 'trainer',
            'username' => 'MainTrainer',
            'email' => 'trainer@localhost',
            'password' => Hash::make('trainer'),
            'name' => 'Trainer',
            'surname' => 'Trainer',
            'phone' => '000000003',
        ]);

        Trainer::create([
            'user_id' => $trainer->id,
            'home_club_address' => '1',
            'expertise' => 'Yoga',
            'education' => 'Yoga Degree',
            'languages' => 'English, Spanish',
            'content_about' => 'I am a trainer at basic fit',
            'stripe_id' => '000000',
            'stripe_url'=> 'stripeTrainer@stripe.com',
            'rate_currency' => 'EUR',
            'rate_amount' => '300',
        ]);
    }
}
