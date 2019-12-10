<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuysItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buys_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('idProduct');
            $table->unsignedInteger('idStock');
            $table->unsignedInteger('idBuy');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->string('state',2);
            $table->foreign('idProduct')->references('id')->on('products');
            $table->foreign('idStock')->references('id')->on('stocks');
            $table->foreign('idBuy')->references('id')->on('buys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function(Blueprint $table){
            $table->dropForeign('[idProduct]');
            $table->dropForeign('[idStock]');
            $table->dropForeign('[idBuy]');
        });
        Schema::dropIfExists('buys_items');
    }
}
