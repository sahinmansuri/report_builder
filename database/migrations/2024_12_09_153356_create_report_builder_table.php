<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('report_builder', function (Blueprint $table) {
        // Use UUID as the primary key
        $table->uuid('id')->primary(); // UUID as primary key
        $table->uuid('parent_id')->nullable();
        $table->string('field_name')->nullable();
        $table->string('field_type')->nullable(); // select, radio, file, checkbox, text, textarea, complex, datepicker
        $table->json('option_info')->nullable(); // only for static input (radio, dropdown, checkbox)
        $table->boolean('dynamic')->default(false); // will apply to select, radio, checkbox
        $table->json('master_table_info')->nullable(); // {table_name:"countries", key_field: "id", value_field: "name"}
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_builder');
    }
};
