<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creator', function (Blueprint $table) {
            $table->id();
            $table->string("banner");
            $table->string("description");
            $table->string("instagram");
            $table->string("youtube");
            $table->float("costVip");
            $table->boolean("hasMercadoPago")->default(false);
            $table->foreignId("idUser")->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();

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
        Schema::dropIfExists('creator');
    }
}
