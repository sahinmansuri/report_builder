<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'year',               // Corresponds to 'name' column
        'degree_id',              // Corresponds to 'email' column
        'university',             // Corresponds to 'gender' column
        'result',      // Corresponds to 'date_of_birth' column
    ];

}
