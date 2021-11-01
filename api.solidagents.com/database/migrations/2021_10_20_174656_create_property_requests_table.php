<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->string("type");
            $table->float("lowest_price")->default(0);
            $table->float("highest_price")->default(0);
            $table->string('state');
            $table->string('lga');
            $table->string('town');
            $table->string('deal');
            $table->string("detail")->default('');
            $table->string("description")->default('');
            $table->boolean("still_needed")->default(false);
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
        Schema::dropIfExists('property_requests');
    }
}
