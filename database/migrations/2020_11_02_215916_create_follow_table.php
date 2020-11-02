<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow', function (Blueprint $table) {
            $table->foreignId("idUser");
            $table->foreignId("idCreator");
            $table->boolean("isVip");
            $table->timestamps();

            $table->foreign("idUser")->references("id")->on("user");
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
        Schema::dropIfExists('follow');
    }
}
