<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('storekeeper_id')->default(1);
            $table->unsignedInteger('payment_id')->nullable();
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedInteger('billing_address_id')->nullable();
            $table->unsignedInteger('delivery_address_id')->nullable();
            $table->foreign('billing_address_id')->references('id')->on('addresses');
            $table->foreign('delivery_address_id')->references('id')->on('addresses');
//            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('payment_id')->references('id')->on('payments');
//            $table->foreign('status_id')->references('id')->on('statuses');
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
        Schema::table('commands', function(Blueprint $table){
            $table->dropForeign('[billing_address_id]');
            $table->dropForeign('[delivery_address_id]');
            $table->dropForeign('[client_id]');
            $table->dropForeign('[payment_id]');
            $table->dropForeign('[status_id]');
        });
        Schema::dropIfExists('commands');
    }
}
