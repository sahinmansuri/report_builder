<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Degree;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ReportBuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Flush existing records in the 'report_builder' table
        DB::table('report_builder')->truncate();

        // Fetch dynamic options for countries, languages, and degrees
        $education_id = Str::uuid();

        // Insert new entries into the 'report_builder' table
        DB::table('report_builder')->insert([
            [
                'id' => Str::uuid(), // Generate a UUID for the primary key
                'parent_id' => null,
                'field_name' => 'name',
                'field_type' => 'text', // Text field
                'option_info' => null,
                'dynamic' => false,
                'master_table_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(), // Generate a UUID for the primary key
                'parent_id' => null,
                'field_name' => 'email',
                'field_type' => 'email', // Text field
                'option_info' => null,
                'dynamic' => false,
                'master_table_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(), // Generate a UUID for the primary key
                'parent_id' => null,
                'field_name' => 'file_name',
                'field_type' => 'text', // Text field
                'option_info' => null,
                'dynamic' => false,
                'master_table_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'field_name' => 'gender',
                'field_type' => 'radio', // Radio button
                'option_info' => json_encode([
                    '0' => 'Male',
                    '1' => 'Female',
                    '2' => 'Other'
                ]),
                'dynamic' => false,
                'master_table_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'field_name' => 'country_id',
                'field_type' => 'select', // Select dropdown
                'option_info' => null,
                'dynamic' => true, // Dynamic field
                'master_table_info' => json_encode(["key_field"=> "id", "table_name"=> "countries", "value_field"=> "name"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'field_name' => 'date_of_birth',
                'field_type' => 'date', // Date picker
                'option_info' => null,
                'dynamic' => false,
                'master_table_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'field_name' => 'address',
                'field_type' => 'textarea', // Textarea
                'option_info' => null,
                'dynamic' => false,
                'master_table_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'field_name' => 'language_id',
                'field_type' => 'checkbox', // Select dropdown
                'option_info' => null,
                'dynamic' => true, // Dynamic field
                'master_table_info' => json_encode(["key_field"=> "id", "table_name" => "languages", "pivot_table"=> "language_user", "value_field" => "name", "pivot_select_fields"=> ["language_id"], "pivot_main_key_field"=> "user_id"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $education_id,
                'parent_id' => null,
                'field_name' => 'education',
                'field_type' => 'complex',
                'option_info' => null,
                'dynamic' => true, // Dynamic field
                'master_table_info' => json_encode(["key_field"=> "id", "table_name"=> "educations", "pivot_table"=> "educations", "value_field"=> "name", "pivot_select_fields"=> ["year", "degree_id", "university", "result"], "pivot_main_key_field"=> "user_id"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => $education_id,
                'field_name' => 'degree_id',
                'field_type' => 'select', // Select dropdown for degree
                'option_info' => null,
                'dynamic' => true, // Dynamic field
                'master_table_info' => json_encode(["key_field"=> "id", "table_name"=> "educations", "pivot_table"=> "educations", "value_field"=> "name", "pivot_select_fields"=> ["degree_id"], "pivot_main_key_field"=> "user_id"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => $education_id,
                'field_name' => 'year',
                'field_type' => 'date', // Text field for year
                'option_info' => null,
                'dynamic' => true,
                'master_table_info' => json_encode(["key_field" => "id","table_name" => "educations","pivot_table" => "educations","value_field" => "year","pivot_select_fields" => ["year"],"pivot_main_key_field" => "user_id"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => $education_id,
                'field_name' => 'university',
                'field_type' => 'text', // Text field for university
                'option_info' => null,
                'dynamic' => true,
                'master_table_info' => json_encode(["key_field"=> "id", "table_name"=> "educations", "pivot_table"=> "educations", "value_field"=> "university", "pivot_select_fields"=> ["university"], "pivot_main_key_field"=> "user_id"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => $education_id,
                'field_name' => 'result',
                'field_type' => 'text', // Text field for result
                'option_info' => null,
                'dynamic' => true,
                'master_table_info' => json_encode(["key_field"=> "id", "table_name"=> "educations", "pivot_table"=> "educations", "value_field"=> "result", "pivot_select_fields"=> ["result"], "pivot_main_key_field"=> "user_id"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
