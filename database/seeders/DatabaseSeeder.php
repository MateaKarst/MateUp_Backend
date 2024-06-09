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
use Database\Seeders\userSeeders\adminSeeder;
use Database\Seeders\userSeeders\memberSeeder;
use Database\Seeders\userSeeders\trainerSeeder;
use Database\Seeders\userSeeders\buddiesSeeder;
use Database\Seeders\groupSessionsSeeders\groupSessionsSeeder;

class DatabaseSeeder extends Seeder
{
    // Run the database seeds.
    public function run(): void
    {
        $this->call([
            adminSeeder::class,
            memberSeeder::class,
            trainerSeeder::class,
            buddiesSeeder::class,
            groupSessionsSeeder::class
        ]);
    }
}
