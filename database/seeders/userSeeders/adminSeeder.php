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

class adminSeeder extends Seeder
{

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
    }
}
