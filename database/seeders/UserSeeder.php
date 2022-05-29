<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'blocked' => $faker->boolean(),
            ]);
        }
    }
}
