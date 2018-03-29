<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 320)->nullable();
            $table->boolean('is_group')->default(0);
            $table->text('encryption_key')->nullable();

            $table->unsignedInteger('last_message_id')->nullable();
            $table->timestamp('last_message_at', 0)->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['last_message_at']);
            $table->index(['name']);
            $table->index(['name', 'last_message_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
