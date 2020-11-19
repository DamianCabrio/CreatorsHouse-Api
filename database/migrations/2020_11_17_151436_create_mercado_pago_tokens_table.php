<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercadoPagoTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercado_pago_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId("idCreador")->unique();
            $table->integer("expires_in");
            $table->string("access_token");
            $table->string("refresh_token")->nullable();
            $table->timestamps();

            $table->foreign("idCreador")->references("id")->on("creator");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercado_pago_tokens');
    }
}
