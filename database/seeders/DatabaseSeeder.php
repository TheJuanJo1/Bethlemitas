<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RolSeeder;
use Database\Seeders\AsignatureSeeder;
use Database\Seeders\DegreeSeeder;
use Database\Seeders\GroupSeeder;
use Database\Seeders\PeriodSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RolSeeder::class);
        $this->call(AsignatureSeeder::class);
        $this->call(DegreeSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(PeriodSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(UserSeeder::class);

    }
}
