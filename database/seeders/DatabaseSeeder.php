<?php

namespace Database\Seeders;

use App\Models\Data;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->count(50)->create(); // Create 50 users
        User::factory()->admin()->create(); // Create 1 admin user
        Data::factory()->count(117)->create();
    }
}
