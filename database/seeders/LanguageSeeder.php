<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $languages = [
            'English',
            'Spanish',
            'French',
            'German',
            'Chinese',
            'Arabic',
            'Russian',
            'Portuguese',
            'Japanese',
            'Italian',
            'Hindi',
            'Bengali',
            'Urdu',
            'Korean',
            'Turkish',
            'Dutch',
            'Polish',
            'Greek',
            'Swedish',
            'Danish',
        ];

        foreach ($languages as $language) {
            DB::table('languages')->insert([
                'name' => $language,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
