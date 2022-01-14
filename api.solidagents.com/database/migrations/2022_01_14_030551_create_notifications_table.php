<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string("target")->nullable();
            $table->string("creator")->nullable(); //could be an admin or an automated reaction responding to users' action
            $table->string("title")->nullable();
            $table->text("body");
            $table->mediumText("viewed_by")->nullable();
            $table->timestamp("start_date")->nullable();
            $table->timestamp("end_date")->nullable();
            $table->tinyInteger("expired")->default(false);
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
        Schema::dropIfExists('notifications');
    }
}
