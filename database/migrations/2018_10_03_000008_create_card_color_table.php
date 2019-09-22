<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_color', function (Blueprint $table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('color_id');
            $table->foreign('card_id')->references('id')->on('cards');
//            $table->foreign('color_id')->references('id')->on('colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_color', function(Blueprint $table){
            $table->dropForeign('[card_id]');
            $table->dropForeign('[color_id]');
        });
        Schema::dropIfExists('card_color');
    }
}
