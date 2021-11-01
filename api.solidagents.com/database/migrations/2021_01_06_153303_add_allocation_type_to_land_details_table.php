<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllocationTypeToLandDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land_details', function (Blueprint $table) {
            $table->string('allocation_type')->nullable()->default(null)->change();
            $table->string('size')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('land_details', function (Blueprint $table) {
            $table->string('allocation_type');
            $table->string('size');
        });
    }
}
