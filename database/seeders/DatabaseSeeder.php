<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Password;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        if (User::where('email', '!=', 'keesrijpstrat@gmail.com')) 
        {
            User::factory()->create([
                'name' => 'keesrijpstrat@gmail.com',
                'email' => 'keesrijpstrat@gmail.com',
                'password' => bcrypt('!Peperklip11!'),
            ]);
        }


        Password::factory(10)->create();
        
    }
}
