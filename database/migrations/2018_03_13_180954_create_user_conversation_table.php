<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserConversationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_conversation', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('conversation_id');

            $table->string('color')->nullable();
            $table->boolean('is_owner')->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'conversation_id']);

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
        Schema::dropIfExists('user_conversation');
    }
}
