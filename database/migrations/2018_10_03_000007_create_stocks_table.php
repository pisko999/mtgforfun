<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('initial_price');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->string('language')->default('EN');
            $table->string('state',2)->default('MT');
            $table->string('idArticleMKM')->nullable(); //idArticle from mkm
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
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
            $table->dropForeign('[product_id]');
        });
        Schema::dropIfExists('stocks');
    }
}
