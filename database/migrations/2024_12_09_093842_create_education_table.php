<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('year')->nullable();
            $table->unsignedBigInteger('degree_id')->nullable(); // Define the degree_id column here
            $table->string('university')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();

            // Add the foreign key constraint
            $table->foreign('degree_id')->references('id')->on('degrees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
