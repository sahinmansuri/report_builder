<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Degree;
use App\Models\Education;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a Faker instance
        $faker = Faker::create();

        $countries = Country::all();
        $degrees = Degree::all();
        $languages = Language::all();

        // Create 20 fake users
        foreach (range(1, 20) as $index) {

            $randomCountry = $countries->random();
            $randomDegree = $degrees->random();

            // Create the user
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->address,
                'country_id' => $randomCountry->id,
                'gender' => $faker->randomElement([1, 2, 3]),
                'date_of_birth' => $faker->date('Y-m-d', 'now'), // Generates a random date in the format YYYY-MM-DD
            ]);

            $results = ['A', 'B', 'C', 'D', 'E'];

            // Create education record for the user
            $education = Education::create([
                'user_id' => $user->id,
                'year' => $faker->year() . '-' . ($faker->year() + 1), // Example: "2021-2022"
                'degree_id' => $randomDegree->id,
                'university' => $faker->word . ' University', // Example of university name
                'result' => $faker->randomElement($results), // Random result from the array
            ]);



            $languageIds = $languages->random(rand(1, 3))->pluck('id'); // Randomly pick 1 to 3 language IDs

            foreach ($languageIds as $languageId) {
                DB::table('language_user')->insert([
                    'user_id' => $user->id,
                    'language_id' => $languageId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

