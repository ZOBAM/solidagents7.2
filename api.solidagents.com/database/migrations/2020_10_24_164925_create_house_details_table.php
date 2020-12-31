<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->integer('total_rooms');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->boolean('parking_space');
            $table->boolean('first_resident')->default(null);
            $table->integer('parking_space_size');
            $table->string('furnishing');
            $table->string('housing_quality');
            $table->boolean('smoking');
            $table->boolean('pets');
            $table->boolean('parties');
            $table->float('minimum_rent');
            $table->string('water');
            $table->string('light');
            $table->string('fenced');
            $table->string('facilities');
            $table->integer('guest_capacity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_details');
    }
}
