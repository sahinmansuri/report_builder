<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     public function run()
     {
         // Array of degrees to seed into the database
         $degrees = [
             ['name' => 'Bachelor of Science'],
             ['name' => 'Bachelor of Arts'],
             ['name' => 'Master of Science'],
             ['name' => 'Master of Arts'],
             ['name' => 'Doctor of Philosophy'],
             ['name' => 'Associate Degree'],
             ['name' => 'Diploma'],
             ['name' => 'Certificate'],
             // Add more degrees as needed
         ];

         // Insert degrees into the 'degrees' table
         DB::table('degrees')->insert($degrees);
     }
}
