<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');

            $table->text('body')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('conversation_id');
            $table->unsignedInteger('type');
            $table->json('statuses');

            $table->timestamps();

            $table->index(['user_id', 'conversation_id']);
            $table->index(['conversation_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('conversation_id')->references('id')->on('conversations');
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
