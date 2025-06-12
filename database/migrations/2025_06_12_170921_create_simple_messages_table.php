<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimpleMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('simple_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->text('body');
            $table->timestamps();

            $table->foreign('sender_id')
                  ->references('id')->on('site_users')
                  ->onDelete('cascade');
            $table->foreign('recipient_id')
                  ->references('id')->on('site_users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('simple_messages');
    }
}
