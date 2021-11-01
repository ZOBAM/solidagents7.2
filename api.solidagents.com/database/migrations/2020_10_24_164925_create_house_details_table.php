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
            $table->integer('bedrooms')->nullable()->default(null);
            $table->integer('bathrooms')->nullable()->default(null);
            $table->boolean('parking_space')->default(0);
            $table->boolean('first_resident')->default(null);
            $table->integer('parking_space_size')->nullable()->default(null);
            $table->string('furnishing')->nullable()->default('Not furnished');
            $table->string('housing_quality')->nullable()->default('standard');
            $table->boolean('smoking')->nullable()->default(null);
            $table->boolean('pets')->nullable()->default(null);
            $table->boolean('parties')->nullable()->default(null);
            $table->float('minimum_rent')->nullable()->default(null);
            $table->string('water')->default("not_available");
            $table->string('light')->default(0);
            $table->string('fenced')->default("no");
            $table->string('facilities')->nullable()->default(null);
            $table->integer('guest_capacity')->nullable()->default(null);
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
