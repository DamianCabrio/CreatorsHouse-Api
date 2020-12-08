<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->id();
            $table->text("text");
            $table->foreignId("idPost");
            $table->foreignId("idUser");
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("idPost")->references("id")->on("post");
            $table->foreign("idUser")->references("id")->on("user");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment');
    }
}
