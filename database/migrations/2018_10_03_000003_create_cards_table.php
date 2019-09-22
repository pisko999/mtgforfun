<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedSmallInteger('edition_id');
            $table->string('rarity',16);
            $table->integer('number');
            $table->string('promo', 4)->nullable();
            $table->string('mana_cost',64)->nullable();
            $table->string('text',1024)->nullable();
            $table->string('flavor',1024)->nullable();
            $table->boolean('foil');
            $table->boolean('exists_foil');
            $table->boolean('exists_nonfoil');
            $table->foreign('id')->references('id')->on('products');
            //$table->foreign('edition_id')->references('id')->on('editions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function(Blueprint $table){
            $table->dropForeign('[id]');
            //$table->dropForeign('[edition_id]');

        });
        Schema::dropIfExists('cards');
    }
}
