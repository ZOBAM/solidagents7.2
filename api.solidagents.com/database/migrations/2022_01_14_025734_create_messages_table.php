<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("sender_id")->unsigned();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger("receiver_id")->unsigned();
            $table->bigInteger("property_id")->unsigned();
            $table->text("chat");
            $table->smallInteger("viewed")->default(false);
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
        Schema::dropIfExists('messages');
    }
}
