<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('MT');
            $table->unsignedInteger('NM');
            $table->unsignedInteger('EX');
            $table->unsignedInteger('GD');
            $table->unsignedInteger('LP');
            $table->unsignedInteger('PL');
            $table->unsignedInteger('PO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
