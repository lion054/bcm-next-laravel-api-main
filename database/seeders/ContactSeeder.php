<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contact::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {
            Contact::create([
                'phone' => random_int(1000000000, 9999999999),
                'type' => $faker->randomElement(['Home', 'Office']),
                'user_id' => $faker->numberBetween(0, 9),
            ]);
        }
    }
}
