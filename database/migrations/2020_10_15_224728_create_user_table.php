<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('avatar');
            $table->date('birthDate');
            $table->unsignedInteger('dni');
            $table->string('password');
            $table->boolean('isCreator')->default(false);
            $table->boolean('isAdmin')->default(false);
            $table->string('verificationToken')->nullable();
            $table->boolean('isVerified')->default(false);
            $table->softDeletes();
            $table->rememberToken();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
