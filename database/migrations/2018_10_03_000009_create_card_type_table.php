<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_type', function (Blueprint $table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('type_id');
            $table->foreign('card_id')->references('id')->on('cards');
//            $table->foreign('type_id')->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_type', function(Blueprint $table){
            $table->dropForeign('[card_id]');
            $table->dropForeign('[type_id]');
        });
        Schema::dropIfExists('card_type');
    }
}
