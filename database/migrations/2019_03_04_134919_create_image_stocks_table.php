<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_stocks', function (Blueprint $table) {
            $table->string('alt');
            $table->string('path');
            $table->unsignedInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('stocks');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images_stocks', function(Blueprint $table){
            $table->dropForeign('[stock_id]');
        });
        Schema::dropIfExists('images_stocks');
    }
}
