<?php

namespace Database\Seeders;

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
        //- Cargar los worldseeders
        $this->call(WorldTableSeeder::class);

        User::factory()->create([
            'name' => 'Jan Carlos Jaimes',
            'email' => 'softsispro@gmail.com',
            'password' => bcrypt('Carlos0723+'),
        ]);


    }
}
