<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardSubtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_subtype', function (Blueprint $table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('subtype_id');
            $table->foreign('card_id')->references('id')->on('cards');
//            $table->foreign('subtype_id')->references('id')->on('subtypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_subtype', function(Blueprint $table){
            $table->dropForeign('[card_id]');
            $table->dropForeign('[subtype_id]');
        });
        Schema::dropIfExists('card_subtype');
    }
}
