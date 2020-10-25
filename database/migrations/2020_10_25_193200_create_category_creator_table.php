<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryCreatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_creator', function (Blueprint $table) {
            $table->id();

            $table->foreignId("category_id");
            $table->foreignId("creator_id");

            $table->foreign("category_id")->references("id")->on("category");
            $table->foreign("creator_id")->references("id")->on("creator");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_creator');
    }
}
