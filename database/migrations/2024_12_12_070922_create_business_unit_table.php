<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_unit', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('business_id'); // Foreign key
            $table->string('business_unit_id')->unique(); // Unique identifier
            $table->string('business_unit_name'); // Business unit name
            $table->string('business_logo')->nullable(); // Logo path
            $table->string('mobile_number'); // Mobile number
            $table->string('whatsapp_number')->nullable(); // WhatsApp number
            $table->string('user_name'); // User name
            $table->string('password'); // Password
            $table->string('locality')->nullable(); // Locality
            $table->string('pincode', 10)->nullable(); // Pincode
            $table->string('city')->nullable(); // City
            $table->string('town')->nullable(); // Town
            $table->string('state')->nullable(); // State
            $table->string('country')->nullable(); // Country
            $table->text('full_address')->nullable(); // Full address
            $table->string('status')->default('active'); // Status column
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_unit');
    }
};
