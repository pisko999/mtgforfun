<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLegendariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legendaries', function (Blueprint $table) {
            $table->unsignedInteger('card_id');
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
        Schema::table('legendaries', function(Blueprint $table){
            $table->dropForeign('[card_id]');
        });
        Schema::dropIfExists('legendaries');
    }
}
