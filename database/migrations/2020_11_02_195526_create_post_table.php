<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->text("content");
            $table->dateTime("date");
            $table->foreignId("idCreator");
            $table->unsignedInteger("tipo");
            $table->string("title");
            $table->boolean("isPublic");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("idCreator")->references("id")->on("creator");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
}
