<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaneswalkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planeswalkers', function (Blueprint $table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('loyalty');
            $table->foreign('card_id')->references('id')->on('cards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planeswalkers', function(Blueprint $table){
            $table->dropForeign('[card_id]');
        });
        Schema::dropIfExists('planeswalkers');
    }
}
