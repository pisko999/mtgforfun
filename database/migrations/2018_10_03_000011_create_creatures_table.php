<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creatures', function (Blueprint $table) {
            $table->unsignedInteger('card_id');
            $table->string('power',4);
            $table->string('toughness',4);
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
        Schema::table('creatures', function(Blueprint $table){
            $table->dropForeign('[card_id]');
        });
        Schema::dropIfExists('creatures');
    }
}
