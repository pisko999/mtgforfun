<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boosters', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedSmallInteger('edition_id');
            $table->unsignedSmallInteger('cards');
            $table->foreign('product_id')->references('id')->on('products');
//            $table->foreign('edition_id')->references('id')->on('editions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boosters', function(Blueprint $table){
            $table->dropForeign('[product_id]');
            $table->dropForeign('[edition_id]');
        });
        Schema::dropIfExists('boosters');
    }
}
