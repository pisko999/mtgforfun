<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoosterBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booster_boxs', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedSmallInteger('edition_id');
            $table->unsignedSmallInteger('boosters');
            $table->unsignedInteger('promo_product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
//            $table->foreign('edition_id')->references('id')->on('editions');
            $table->foreign('promo_product_id')->references('id')->on('products');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booster_boxes', function(Blueprint $table){
            $table->dropForeign('[product_id]');
            $table->dropForeign('[edition_id]');
            $table->dropForeign('[promo_product_id]');
        });
        Schema::dropIfExists('booster_boxes');
    }
}
