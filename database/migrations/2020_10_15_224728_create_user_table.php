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
            $table->string('password');
            $table->string('email')->unique();
            $table->boolean('isCreator')->default(false);
            $table->string('avatar');
            $table->date('birthDate');
            $table->string('name');
            $table->string('surname');
            $table->unsignedInteger('dni');
            $table->boolean('isAdmin')->default(false);
            $table->softDeletes();
            $table->string('verificationToken');
            $table->boolean('isVerified')->default(false);
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
